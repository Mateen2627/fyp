<?php

namespace Database\Seeders;

use App\Models\Section;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    public function run()
    {
        foreach (NumlSeedData::classes() as $class) {
            list($department, $semester) = NumlSeedData::classDetails($class);
            $teachers = NumlSeedData::teachers($department);
            foreach (NumlSeedData::SECTIONS as $index => $name) {
                Section::updateOrCreate(['my_class_id' => $class->id, 'name' => $name], [
                    'active' => 1,
                    // This existing relationship represents the class incharge.
                    'teacher_id' => $teachers[($semester - 1 + $index) % count($teachers)]->id,
                ]);
            }
        }
    }
}
