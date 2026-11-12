<?php

namespace App\Services;

use App\Models\Course;
use App\Models\TeacherAssignment;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use App\Models\TimeSlot;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class TimetableService
{
    public function lessonData(array $data, $existingId = null)
    {
        $record = TimeTableRecord::findOrFail($data['ttr_id']);
        $slot = TimeSlot::where('ttr_id', $record->id)->findOrFail($data['ts_id']);
        $course = Course::where('my_class_id', $record->my_class_id)->where('subject_id', $data['subject_id'])->first();
        if (!$course || $this->teachers($record, $course->id)->isEmpty()) {
            throw ValidationException::withMessages(['subject_id' => 'Select a subject with an assigned teacher for this semester and section.']);
        }
        $day = $record->exam_id ? null : ($data['day'] ?? null);
        $date = $record->exam_id ? ($data['exam_date'] ?? null) : null;
        if (!$day && !$date) {
            throw ValidationException::withMessages(['day' => 'Choose the lesson day or exam date.']);
        }
        $data = [
            'ttr_id' => $record->id, 'ts_id' => $slot->id, 'subject_id' => $data['subject_id'],
            'day' => $day, 'exam_date' => $date,
            'day_num' => $day ? Carbon::parse($day)->dayOfWeekIso : null,
            'timestamp_from' => strtotime(($date ?: $day).' '.$slot->time_from),
            'timestamp_to' => strtotime(($date ?: $day).' '.$slot->time_to),
        ];
        $this->assertNoClash($record, $slot, $course->id, $day, $date, $existingId);
        return $data;
    }

    public function teachers(TimeTableRecord $record, $courseId)
    {
        return TeacherAssignment::where('course_id', $courseId)->where('my_class_id', $record->my_class_id)
            ->whereHas('teacher')->where(function ($q) use ($record) {
                $q->whereNull('section_id');
                if ($record->section_id) { $q->orWhere('section_id', $record->section_id); }
            })->pluck('teacher_id');
    }

    public function validateSubjectLessons($subjectId)
    {
        foreach (TimeTable::where('subject_id', $subjectId)->get() as $lesson) {
            $this->lessonData($lesson->toArray(), $lesson->id);
        }
    }

    private function assertNoClash($record, $slot, $courseId, $day, $date, $excludeId)
    {
        $teachers = $this->teachers($record, $courseId);
        $start = Carbon::parse($slot->time_from)->format('H:i');
        $end = Carbon::parse($slot->time_to)->format('H:i');
        $others = TimeTable::with(['tt_record', 'time_slot'])->whereHas('tt_record', function ($q) use ($record) { $q->where('year', $record->year); });
        if ($excludeId) { $others->where('id', '<>', $excludeId); }
        foreach ($others->get() as $other) {
            if (!$other->tt_record || !$other->time_slot) { continue; }
            $sameDay = $date
                ? ($other->exam_date ? Carbon::parse($other->exam_date)->isSameDay(Carbon::parse($date)) : $other->day === Carbon::parse($date)->format('l'))
                : ($other->exam_date ? Carbon::parse($other->exam_date)->format('l') === $day : $other->day === $day);
            if (!$sameDay || $start >= Carbon::parse($other->time_slot->time_to)->format('H:i') || $end <= Carbon::parse($other->time_slot->time_from)->format('H:i')) { continue; }
            $sameClass = (int) $other->tt_record->my_class_id === (int) $record->my_class_id;
            $sameSection = !$record->section_id || !$other->tt_record->section_id || (int) $other->tt_record->section_id === (int) $record->section_id;
            $otherCourse = Course::where('subject_id', $other->subject_id)->where('my_class_id', $other->tt_record->my_class_id)->first();
            $teacherClash = $otherCourse && $teachers->intersect($this->teachers($other->tt_record, $otherCourse->id))->isNotEmpty();
            if (($sameClass && $sameSection) || $teacherClash) {
                throw ValidationException::withMessages(['ts_id' => 'This lesson overlaps an existing lesson for the section or its subject teacher.']);
            }
        }
    }
}
