<?php

require __DIR__.'/verify_numl_seed.php';

use App\Models\AttendanceCheckIn;
use App\Models\Course;
use App\Models\Section;
use App\Models\StudentAttendance;
use App\Models\TimeSlot;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

$faceRequests = 0;
$temporaryRoot = sys_get_temp_dir().'/numl-face-test-'.bin2hex(random_bytes(6));
config([
    'filesystems.default' => 'public',
    'filesystems.disks.local.root' => $temporaryRoot.'/private',
    'filesystems.disks.public.root' => $temporaryRoot.'/public',
    'services.face_verification.url' => 'http://face-service.test/verify-face',
]);
Storage::forgetDisk(['local', 'public']);
Carbon::setTestNow(Carbon::parse('2026-11-12 09:15:00', 'Asia/Karachi'));

function faceRequest($user, $method, $uri, array $data = [], $expected = 200, array $files = [], $code = null)
{
    global $faceRequests;
    // Isolate HTTP cases from each other's rate counter; keep auth/role middleware active.
    Cache::flush();
    Auth::forgetGuards();
    if ($user) { Auth::guard()->setUser($user->fresh()); }
    $request = Request::create($uri, $method, $data, [], $files, ['HTTP_ACCEPT' => $method === 'GET' ? 'text/html' : 'application/json']);
    $kernel = app(Kernel::class);
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);
    $faceRequests++;
    checkSeed($response->getStatusCode() === $expected, $method.' '.$uri.' expected '.$expected.', got '.$response->getStatusCode().': '.substr((string) $response->getContent(), 0, 2000));
    if ($code !== null) { checkSeed(json_decode($response->getContent(), true)['code'] === $code, 'Unexpected error code.'); }
    return $response;
}

function fakeFaceResponse($body, $status = 200, $inspect = null)
{
    Http::swap(new Factory());
    Http::fake(function ($request) use ($body, $status, $inspect) {
        checkSeed($request->url() === config('services.face_verification.url'), 'Wrong recognition service URL.');
        checkSeed($request->hasFile('reference_image') && $request->hasFile('live_image'), 'Multipart image names do not match FastAPI.');
        if ($inspect) { $inspect($request); }
        if ($body instanceof Throwable) { throw $body; }
        return Http::response($body, $status);
    });
}

try {
    $student = User::where('username', 'marjan')->firstOrFail();
    $admin = User::where('username', 'admin')->firstOrFail();
    $coordinator = User::where('username', 'zainulabideen')->firstOrFail();
    $otherCoordinator = User::where('username', 'mustajeeb')->firstOrFail();
    $hod = User::where('username', 'neelofer')->firstOrFail();
    $teacher = User::where('username', 'faisal')->firstOrFail();
    $record = $student->student_record;
    $sectionB = Section::where('my_class_id', $record->my_class_id)->where('name', 'B')->firstOrFail();
    $studentB = $sectionB->student_record()->firstOrFail()->user;
    $itStudent = User::where('user_type', 'student')->whereHas('student_record.my_class.class_type', function ($q) { $q->where('code', 'IT'); })->firstOrFail();
    $course = Course::where('my_class_id', $record->my_class_id)->where('title', 'Automata Theory')->firstOrFail();
    $timetable = TimeTableRecord::create(['name' => 'Face test timetable', 'my_class_id' => $record->my_class_id, 'section_id' => $record->section_id, 'year' => '2023-2027']);
    $slot = TimeSlot::create(['ttr_id' => $timetable->id, 'hour_from' => 9, 'min_from' => '00', 'meridian_from' => 'AM', 'hour_to' => 10, 'min_to' => '00', 'meridian_to' => 'AM', 'time_from' => '9:00 AM', 'time_to' => '10:00 AM', 'timestamp_from' => 0, 'timestamp_to' => 3600, 'full' => '9:00 AM - 10:00 AM']);
    $lesson = TimeTable::create(['ttr_id' => $timetable->id, 'ts_id' => $slot->id, 'subject_id' => $course->subject_id, 'day' => 'Thursday', 'day_num' => 4, 'timestamp_from' => 0, 'timestamp_to' => 3600]);
    $capture = function () { return ['live_image' => UploadedFile::fake()->image('live.jpg', 200, 200)]; };
    $payload = ['time_table_id' => $lesson->id];
    fakeFaceResponse(['verified' => true, 'distance' => 0.1, 'threshold' => 0.25]);

    faceRequest(null, 'POST', '/attendance/mark-face', $payload, 401, $capture());
    faceRequest($teacher, 'POST', '/attendance/mark-face', $payload, 403, $capture());
    faceRequest($admin, 'POST', '/attendance/mark-face', $payload, 403, $capture());
    faceRequest($studentB, 'POST', '/attendance/mark-face', $payload, 403, $capture(), 'wrong_class');
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 422, $capture(), 'reference_photo_missing');
    checkSeed(Http::recorded()->isEmpty(), 'An unauthorized or duplicate request called the face service.');

    $values = ['name' => $student->name, 'username' => $student->username, 'email' => $student->email, 'user_type' => 'student', 'my_class_id' => $record->my_class_id, 'section_id' => $record->section_id, 'session' => $record->session, 'adm_no' => $record->adm_no, 'year_admitted' => 2023];
    faceRequest($student, 'PUT', '/admin/users/'.$student->id, $values, 403, ['reference_photo' => UploadedFile::fake()->image('official.jpg')]);
    faceRequest($teacher, 'PUT', '/admin/users/'.$student->id, $values, 403, ['reference_photo' => UploadedFile::fake()->image('official.jpg')]);
    faceRequest($admin, 'PUT', '/admin/users/'.$student->id, array_merge($values, ['reference_photo_path' => 'student_references/forged.jpg']), 422);
    faceRequest($admin, 'PUT', '/admin/users/'.$student->id, $values, 422, ['reference_photo' => UploadedFile::fake()->create('fake.jpg', 1, 'text/plain')]);
    faceRequest($admin, 'PUT', '/admin/users/'.$student->id, $values, 302, ['reference_photo' => UploadedFile::fake()->image('official.jpg')]);
    $firstReference = $student->fresh()->reference_photo_path;
    checkSeed(strpos($firstReference, 'student_references/') === 0 && Storage::disk('public')->exists($firstReference), 'Admin photo was not stored correctly.');
    faceRequest($admin, 'GET', '/admin/users/'.$student->id.'/reference-photo');
    faceRequest($student, 'GET', '/admin/users/'.$student->id.'/reference-photo', [], 403);
    faceRequest($admin, 'PUT', '/admin/users/'.$student->id, $values, 302);
    checkSeed($student->fresh()->reference_photo_path === $firstReference, 'An empty upload removed the official photo.');
    faceRequest($admin, 'PUT', '/admin/users/'.$student->id, $values, 302, ['reference_photo' => UploadedFile::fake()->image('replacement.png')]);
    $reference = $student->fresh()->reference_photo_path;
    checkSeed($reference !== $firstReference && !Storage::disk('public')->exists($firstReference), 'Reference replacement left the old photo.');
    $coordinatorList = faceRequest($coordinator, 'GET', '/academic/reference-photos');
    checkSeed(strpos($coordinatorList->getContent(), 'name="reference_photo"') !== false, 'Coordinator reference-photo upload form is missing.');
    faceRequest($coordinator, 'PUT', '/academic/reference-photos/'.$student->id, [], 302, ['reference_photo' => UploadedFile::fake()->image('coordinator.jpg')]);
    $coordinatorReference = $student->fresh()->reference_photo_path;
    checkSeed($coordinatorReference !== $reference && Storage::disk('public')->exists($coordinatorReference) && !Storage::disk('public')->exists($reference), 'Coordinator could not replace and safely rotate the official photo.');
    faceRequest($coordinator, 'GET', '/academic/reference-photos/'.$student->id);
    faceRequest($hod, 'PUT', '/academic/reference-photos/'.$student->id, [], 403, ['reference_photo' => UploadedFile::fake()->image('hod.jpg')]);
    faceRequest($otherCoordinator, 'PUT', '/academic/reference-photos/'.$student->id, [], 404, ['reference_photo' => UploadedFile::fake()->image('wrong-department.jpg')]);
    faceRequest($coordinator, 'PUT', '/academic/reference-photos/'.$itStudent->id, [], 404, ['reference_photo' => UploadedFile::fake()->image('wrong-department.jpg')]);
    faceRequest($student, 'GET', '/academic/reference-photos', [], 403);
    faceRequest(null, 'GET', '/storage/'.$coordinatorReference, [], 403);
    $reference = $coordinatorReference;
    checkSeed(!array_key_exists('reference_photo_path', $student->fresh()->toArray()), 'Reference path leaked in user serialization.');
    faceRequest($student, 'PUT', '/my_account', ['address' => 'Islamabad, Pakistan', 'reference_photo_path' => $firstReference], 422);
    faceRequest($student, 'POST', '/my_account/photo', ['reference_photo_path' => $firstReference], 422, ['photo' => UploadedFile::fake()->image('profile.jpg')]);
    faceRequest($student, 'POST', '/my_account/photo', [], 302, ['photo' => UploadedFile::fake()->image('profile.jpg')]);
    checkSeed($student->fresh()->reference_photo_path === $reference, 'A self-service profile update altered the official reference.');
    faceRequest($admin, 'GET', '/admin/users/create');
    $edit = faceRequest($admin, 'GET', '/admin/users/'.$student->id.'/edit');
    checkSeed(strpos($edit->getContent(), 'enctype="multipart/form-data"') !== false && strpos($edit->getContent(), 'name="reference_photo"') !== false, 'Admin upload form is missing.');
    $view = faceRequest($student, 'GET', '/student/attendance');
    checkSeed(strpos($view->getContent(), 'attendance/mark-face') !== false && strpos($view->getContent(), 'X-CSRF-TOKEN') !== false, 'Webcam UI is not wired to the canonical endpoint.');
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 422, [], 'invalid_capture');
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 422, ['live_image' => UploadedFile::fake()->create('fake.jpg', 1, 'text/plain')], 'invalid_capture');

    $failureCases = [
        [['verified' => false, 'distance' => 0.8, 'threshold' => 0.25], 200, 422, 'face_mismatch'],
        [['verified' => false, 'error' => 'Spoof detected in given image'], 200, 422, 'spoof_detected'],
        [['verified' => false, 'error' => 'Face could not be detected'], 200, 422, 'face_not_detected'],
        [['verified' => 'true', 'distance' => 0.1, 'threshold' => 0.25], 200, 502, 'invalid_service_response'],
        [['verified' => 1, 'distance' => 0.1, 'threshold' => 0.25], 200, 502, 'invalid_service_response'],
        [['verified' => true], 200, 502, 'invalid_service_response'],
        [['verified' => true, 'distance' => 0.1, 'threshold' => 0.5], 200, 502, 'invalid_service_response'],
        [['verified' => true, 'distance' => 0.4, 'threshold' => 0.25], 200, 422, 'face_mismatch'],
        [['verified' => true, 'distance' => 0.1, 'threshold' => 0.25, 'error' => 'Spoof detected'], 200, 502, 'invalid_service_response'],
        ['not JSON', 200, 502, 'invalid_service_response'],
        [['detail' => 'Model error'], 503, 502, 'service_error'],
        [new ConnectionException('Service is offline'), 200, 503, 'service_unavailable'],
    ];
    foreach ($failureCases as $failure) {
        fakeFaceResponse($failure[0], $failure[1]);
        faceRequest($student, 'POST', '/attendance/mark-face', array_merge($payload, ['verified' => true, 'user_id' => $studentB->id, 'reference_photo_path' => 'another.jpg']), $failure[2], $capture(), $failure[3]);
        checkSeed(StudentAttendance::count() === 0 && AttendanceCheckIn::count() === 0, 'Failed verification created attendance or a check-in.');
    }

    fakeFaceResponse(['verified' => true, 'distance' => 0.1, 'threshold' => 0.25]);
    $student->update(['reference_photo_path' => 'student_references/missing.jpg']);
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 422, $capture(), 'reference_photo_missing');
    $student->update(['reference_photo_path' => '../private.jpg']);
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 422, $capture(), 'reference_photo_missing');
    $student->update(['reference_photo_path' => $reference]);
    checkSeed(Http::recorded()->isEmpty(), 'An unauthorized or duplicate request called the face service.');

    fakeFaceResponse(['verified' => true, 'distance' => 0.1, 'threshold' => 0.25], 200, function () use ($student, $firstReference) {
        $student->update(['reference_photo_path' => $firstReference]);
    });
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 409, $capture(), 'reference_photo_changed');
    checkSeed(StudentAttendance::count() === 0, 'A reference-photo change during verification bypassed the recheck.');
    $student->update(['reference_photo_path' => $reference]);

    // A placeholder pending check-in can be retried with the real verifier.
    Storage::disk('local')->put('attendance_captures/old.jpg', 'old placeholder capture');
    AttendanceCheckIn::create(['user_id' => $student->id, 'time_table_id' => $lesson->id, 'course_id' => $course->id, 'my_class_id' => $record->my_class_id, 'section_id' => $record->section_id, 'attendance_date' => '2026-11-12', 'status' => 'pending', 'image_path' => 'attendance_captures/old.jpg']);
    fakeFaceResponse(['verified' => true, 'distance' => 0.1, 'threshold' => 0.25], 200, function ($request) use ($reference) {
        checkSeed($request->hasFile('reference_image', null, basename($reference)), 'Browser reference replaced the official reference.');
    });
    $success = faceRequest($student, 'POST', '/attendance/mark-face', array_merge($payload, ['user_id' => $studentB->id, 'reference_image' => 'forged']), 200, $capture());
    checkSeed(json_decode($success->getContent(), true)['verified'] === true, 'Success response did not confirm verification.');
    $attendance = StudentAttendance::firstOrFail();
    checkSeed($attendance->user_id === $student->id && $attendance->course_id === $course->id && $attendance->time_table_id === $lesson->id && $attendance->status === 'present' && (bool) $attendance->facial_verified, 'Attendance was saved against the wrong identity, subject, or lesson.');
    checkSeed(AttendanceCheckIn::count() === 1 && AttendanceCheckIn::first()->status === 'verified' && AttendanceCheckIn::first()->image_path === null, 'Pending check-in was not upgraded.');
    checkSeed(!Storage::disk('local')->exists('attendance_captures/old.jpg'), 'Obsolete placeholder capture was retained.');
    fakeFaceResponse(['verified' => true, 'distance' => 0.1, 'threshold' => 0.25]);
    faceRequest($student, 'POST', '/attendance/mark-face', $payload, 409, $capture(), 'attendance_already_marked');
    faceRequest($student, 'POST', '/student/attendance/check-in', $payload, 409, ['capture' => UploadedFile::fake()->image('live.jpg')], 'attendance_already_marked');
    checkSeed(Http::recorded()->isEmpty(), 'An unauthorized or duplicate request called the face service.');
    checkSeed(StudentAttendance::count() === 1, 'Duplicate prevention failed.');

    // A second class on the same day is a separate attendance session.
    $slot2 = $slot->replicate(); $slot2->hour_from = 10; $slot2->hour_to = 11; $slot2->time_from = '10:00 AM'; $slot2->time_to = '11:00 AM'; $slot2->timestamp_from = 3600; $slot2->timestamp_to = 7200; $slot2->save();
    $lesson2 = $lesson->replicate(); $lesson2->ts_id = $slot2->id; $lesson2->save();
    Carbon::setTestNow(Carbon::parse('2026-11-12 10:15:00', 'Asia/Karachi'));
    faceRequest($student, 'POST', '/attendance/mark-face', ['time_table_id' => $lesson2->id], 200, $capture());
    checkSeed(StudentAttendance::count() === 2, 'Daily duplicate logic incorrectly blocked a different lesson.');
    Carbon::setTestNow(Carbon::parse('2026-11-12 17:00:00', 'Asia/Karachi'));
    faceRequest($student, 'POST', '/attendance/mark-face', ['time_table_id' => $lesson2->id], 422, $capture(), 'outside_attendance_window');

    echo 'PASS: '.$faceRequests." face integration HTTP checks; coordinator/admin reference-photo access, multipart contract, rejection paths, duplicates, session scope, service failures and successful attendance.\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'FAIL: '.$error->getMessage()."\n".$error->getTraceAsString()."\n");
    $failed = true;
} finally {
    Carbon::setTestNow();
    if (is_dir($temporaryRoot)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($temporaryRoot, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $entry) { $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname()); }
        rmdir($temporaryRoot);
    }
}
if (!empty($failed)) { exit(1); }
