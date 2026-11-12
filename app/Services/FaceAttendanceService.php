<?php

namespace App\Services;

use App\Exceptions\FaceVerificationException;
use App\Helpers\Qs;
use App\Models\AttendanceCheckIn;
use App\Models\Course;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use App\Models\TimeTable;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FaceAttendanceService
{
    public function mark(User $user, $lessonId, UploadedFile $capture, FaceVerificationService $verifier, array $location)
    {
        list($student, $lesson, $course) = $this->context($user, $lessonId);
        $this->assertWithinCampus($location);
        $date = Carbon::today()->toDateString();
        $this->assertNotMarked($user->id, $lesson->id, $date);
        $referencePhoto = $user->reference_photo_path;
        $verifier->referencePath($user);
        $result = $verifier->verify($user, $capture, $lesson);
        if (($result['verified'] ?? null) !== true) {
            throw new FaceVerificationException('Your face could not be verified. Attendance was not marked.', 422, 'face_not_verified');
        }

        $oldCapture = null;
        $attendance = DB::transaction(function () use ($user, $lessonId, $date, $referencePhoto, &$oldCapture) {
            // Serialize check-ins for a student and recheck state after slow recognition.
            $currentUser = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
            if ($currentUser->reference_photo_path !== $referencePhoto) {
                throw new FaceVerificationException('Your official photo changed during verification. Please try again.', 409, 'reference_photo_changed');
            }
            $currentStudent = StudentRecord::where('user_id', $user->id)->where('grad', 0)->latest('id')->lockForUpdate()->first();
            list($student, $lesson, $course) = $this->context($currentUser, $lessonId, $currentStudent);
            if ($date !== Carbon::today()->toDateString()) {
                throw new FaceVerificationException('The attendance date changed. Please capture a new photo.', 409, 'session_changed');
            }
            $this->assertNotMarked($user->id, $lesson->id, $date);
            $key = ['user_id' => $user->id, 'time_table_id' => $lesson->id, 'attendance_date' => $date];
            $attendance = StudentAttendance::create(array_merge($key, [
                'course_id' => $course->id, 'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id,
                'status' => 'present', 'mode' => 'facial', 'facial_verified' => true, 'marked_by' => $user->id,
                'remarks' => 'Face matched the administrator-managed reference photo with anti-spoofing enabled.',
            ]));
            $previous = AttendanceCheckIn::where('user_id', $user->id)->where('time_table_id', $lesson->id)->whereDate('attendance_date', $date)->first();
            $oldCapture = $previous ? $previous->image_path : null;
            $values = ['course_id' => $course->id, 'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id,
                'status' => 'verified', 'image_path' => null, 'message' => 'Face verified. Attendance marked present.'];
            if ($previous) { $previous->update($values); }
            else { AttendanceCheckIn::create(array_merge($key, $values)); }
            return $attendance;
        });
        // Previous placeholder captures are no longer needed. New selfies are not retained.
        if ($oldCapture && strpos($oldCapture, 'attendance_captures/') === 0 && strpos($oldCapture, '..') === false) {
            Storage::disk('local')->delete($oldCapture);
        }
        return [
            'attendance' => $attendance,
            'distance' => $result['distance'] ?? null,
            'threshold' => $result['threshold'] ?? null,
            'match_confidence' => $result['match_confidence'] ?? null,
            'face_box' => $result['face_box'] ?? null,
            'spoof_check' => $result['spoof_check'] ?? 'passed',
        ];
    }

    private function assertWithinCampus(array $location)
    {
        $latitude = $location['latitude'] ?? null;
        $longitude = $location['longitude'] ?? null;
        $accuracy = $location['accuracy'] ?? null;
        $geofence = config('services.attendance_geofence', []);
        $campusLatitude = $geofence['latitude'] ?? null;
        $campusLongitude = $geofence['longitude'] ?? null;
        $radius = $geofence['radius_meters'] ?? null;
        $maxAccuracy = $geofence['max_accuracy_meters'] ?? null;

        if (!is_numeric($campusLatitude) || !is_numeric($campusLongitude) || !is_numeric($radius) ||
            (float) $campusLatitude < -90 || (float) $campusLatitude > 90 ||
            (float) $campusLongitude < -180 || (float) $campusLongitude > 180 || (float) $radius <= 0) {
            throw new FaceVerificationException('Campus attendance location is not configured. Contact an administrator.', 503, 'campus_location_unconfigured');
        }
        if (!is_numeric($latitude) || !is_numeric($longitude) || !is_numeric($accuracy) ||
            (float) $latitude < -90 || (float) $latitude > 90 ||
            (float) $longitude < -180 || (float) $longitude > 180 || (float) $accuracy <= 0) {
            throw new FaceVerificationException('A valid device location is required to mark attendance.', 422, 'invalid_location');
        }
        if (!is_numeric($maxAccuracy) || (float) $maxAccuracy <= 0 || (float) $accuracy > (float) $maxAccuracy) {
            throw new FaceVerificationException('Your location is not accurate enough. Enable precise location and try again.', 422, 'location_accuracy_insufficient');
        }

        $earthRadius = 6371000;
        $latDelta = deg2rad((float) $latitude - (float) $campusLatitude);
        $lonDelta = deg2rad((float) $longitude - (float) $campusLongitude);
        $lat1 = deg2rad((float) $campusLatitude);
        $lat2 = deg2rad((float) $latitude);
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($lat1) * cos($lat2) * sin($lonDelta / 2) * sin($lonDelta / 2);
        $distance = 2 * $earthRadius * asin(min(1, sqrt($a)));

        if ($distance > (float) $radius) {
            throw new FaceVerificationException(
                'You must be within '.round((float) $radius).' meters of the campus location to mark attendance.',
                403,
                'outside_campus_geofence',
                ['distance_meters' => round($distance, 1), 'radius_meters' => (float) $radius]
            );
        }
    }

    private function context(User $user, $lessonId, StudentRecord $student = null)
    {
        if ($user->user_type !== 'student') {
            throw new FaceVerificationException('Only enrolled students can mark their own attendance.', 403, 'student_required');
        }
        $student = $student ?: StudentRecord::where('user_id', $user->id)->where('grad', 0)->latest('id')->first();
        if (!$student) { throw new FaceVerificationException('No active student enrollment was found.', 403, 'enrollment_missing'); }
        $lesson = TimeTable::with(['tt_record', 'time_slot'])->find($lessonId);
        $record = $lesson ? $lesson->tt_record : null;
        if (!$record || !$lesson->time_slot) { throw new FaceVerificationException('This lesson is no longer available.', 422, 'lesson_unavailable'); }
        if ((int) $record->my_class_id !== (int) $student->my_class_id ||
            ($record->section_id && (int) $record->section_id !== (int) $student->section_id)) {
            throw new FaceVerificationException('You can only mark attendance for your own semester and section.', 403, 'wrong_class');
        }
        if ($record->exam_id || $record->year !== Qs::getCurrentSession() || !$student->section || !(int) $student->section->active) {
            throw new FaceVerificationException('This is not an active class session.', 422, 'inactive_session');
        }
        $today = Carbon::today()->toDateString();
        $start = Carbon::parse($today.' '.$lesson->time_slot->time_from)->subMinutes(15);
        $end = Carbon::parse($today.' '.$lesson->time_slot->time_to);
        if ($lesson->day !== Carbon::today()->format('l') || !Carbon::now()->between($start, $end)) {
            throw new FaceVerificationException('Attendance is available from 15 minutes before your lesson until it ends.', 422, 'outside_attendance_window');
        }
        $course = Course::where('subject_id', $lesson->subject_id)->where('my_class_id', $record->my_class_id)->first();
        if (!$course || app(TimetableService::class)->teachers($record, $course->id)->isEmpty()) {
            throw new FaceVerificationException('No subject teacher is assigned to this lesson.', 422, 'teacher_unassigned');
        }
        return [$student, $lesson, $course];
    }

    private function assertNotMarked($userId, $lessonId, $date)
    {
        $recorded = StudentAttendance::where('user_id', $userId)->where('time_table_id', $lessonId)->whereDate('attendance_date', $date)->exists();
        $verified = AttendanceCheckIn::where('user_id', $userId)->where('time_table_id', $lessonId)->whereDate('attendance_date', $date)->where('status', 'verified')->exists();
        if ($recorded || $verified) {
            throw new FaceVerificationException('Attendance is already marked for this class session today.', 409, 'attendance_already_marked');
        }
    }
}
