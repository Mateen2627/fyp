<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Subject;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        foreach (NumlSeedData::classes() as $class) {
            list($department, $semester) = NumlSeedData::classDetails($class);
            foreach (Subject::where('my_class_id', $class->id)->get() as $subject) {
                // Modern course screens and legacy marks screens use separate tables.
                Course::updateOrCreate(['my_class_id' => $class->id, 'code' => $subject->slug], [
                    'title' => $subject->name,
                    'description' => NumlSeedData::courseDescription($department, $semester, $subject->name),
                ]);
            }
        }
    }
}
