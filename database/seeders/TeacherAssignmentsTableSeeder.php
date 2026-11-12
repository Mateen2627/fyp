<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\DepartmentStaff;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class TeacherAssignmentsTableSeeder extends Seeder
{
    public function run()
    {
        foreach (NumlSeedData::classes() as $class) {
            $hod = DepartmentStaff::where('department_id', $class->class_type_id)->where('is_hod', true)->firstOrFail();
            foreach (Subject::where('my_class_id', $class->id)->get() as $subject) {
                $course = Course::where('my_class_id', $class->id)->where('code', $subject->slug)->firstOrFail();
                if ($course->teaching_initialized) { continue; }
                if (TeacherAssignment::where('course_id', $course->id)->exists()) {
                    $course->update(['teaching_initialized' => true]);
                    continue;
                }
                TeacherAssignment::updateOrCreate([
                    'course_id' => $course->id,
                    'my_class_id' => $class->id,
                    'teacher_id' => $subject->teacher_id,
                    'section_id' => null,
                ], [
                    'assigned_by' => $hod->user_id,
                    'notes' => 'Sample teaching assignment for sections A/B; session '.NumlSeedData::SESSION.'.',
                ]);
                $course->update(['teaching_initialized' => true]);
            }
        }
    }
}
