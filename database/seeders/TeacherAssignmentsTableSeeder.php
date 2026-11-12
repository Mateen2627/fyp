<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\User;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class TeacherAssignmentsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::where('code', 'NUML-ADMIN')->firstOrFail();
        foreach (NumlSeedData::classes() as $class) {
            foreach (Subject::where('my_class_id', $class->id)->get() as $subject) {
                $course = Course::where('my_class_id', $class->id)->where('code', $subject->slug)->firstOrFail();
                TeacherAssignment::updateOrCreate([
                    'course_id' => $course->id,
                    'my_class_id' => $class->id,
                    'teacher_id' => $subject->teacher_id,
                ], [
                    'assigned_by' => $admin->id,
                    'notes' => 'Sample teaching assignment for sections A/B; session '.NumlSeedData::SESSION.'.',
                ]);
            }
        }
    }
}
