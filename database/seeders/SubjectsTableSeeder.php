<?php

namespace Database\Seeders;

use App\Models\Subject;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{
    public function run()
    {
        foreach (NumlSeedData::classes() as $class) {
            list($department, $semester) = NumlSeedData::classDetails($class);
            $teachers = NumlSeedData::teachers($department);
            foreach (NumlSeedData::curriculum($department, $semester) as $index => $name) {
                Subject::firstOrCreate(['my_class_id' => $class->id, 'name' => $name], [
                    'slug' => sprintf('%s-%d%02d', $department, $semester, $index + 1),
                    'teacher_id' => $teachers[($semester - 1 + $index) % count($teachers)]->id,
                ]);
            }
        }
    }
}
