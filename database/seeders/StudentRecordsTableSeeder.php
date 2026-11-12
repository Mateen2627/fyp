<?php

namespace Database\Seeders;

use App\Models\Lga;
use App\Models\Nationality;
use App\Models\Section;
use App\Models\State;
use App\Models\StudentRecord;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentRecordsTableSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('cj');
        $state = State::where('name', 'Islamabad Capital Territory')->firstOrFail();
        $attributes = [
            'nal_id' => Nationality::where('name', 'Pakistani')->firstOrFail()->id,
            'state_id' => $state->id,
            'lga_id' => Lga::where('state_id', $state->id)->where('name', 'Islamabad')->firstOrFail()->id,
            'address' => 'Islamabad, Pakistan',
        ];

        $classes = NumlSeedData::classes();
        $namedClass = $classes->firstWhere('name', NumlSeedData::className('CS', NumlSeedData::NAMED_STUDENTS_SEMESTER));
        $namedSection = Section::where('my_class_id', $namedClass->id)->where('name', 'A')->firstOrFail();

        // Reserve supplied first names before creating synthetic students.
        foreach (NumlSeedData::namedStudents() as $index => $name) {
            $this->createStudent($namedSection, $index + 1, $name, $password, $attributes);
        }

        $nameIndex = 0;
        foreach ($classes as $class) {
            foreach (Section::where('my_class_id', $class->id)->whereIn('name', NumlSeedData::SECTIONS)->orderBy('name')->get() as $section) {
                for ($number = 1; $number <= NumlSeedData::STUDENTS_PER_SECTION; $number++) {
                    if ($section->id === $namedSection->id && $number <= count(NumlSeedData::namedStudents())) {
                        continue;
                    }
                    $this->createStudent($section, $number, NumlSeedData::studentName($nameIndex++), $password, $attributes);
                }
            }
        }
    }

    private function createStudent(Section $section, $number, $name, $password, array $attributes)
    {
        list($department, $semester) = NumlSeedData::classDetails($section->my_class);
        $code = sprintf('NUML-2023-%s-S%d-%s-%03d', $department, $semester, $section->name, $number);
        $user = NumlSeedData::seedUser($code, $name, 'student', $password, $attributes);

        StudentRecord::updateOrCreate(['user_id' => $user->id], [
            'my_class_id' => $section->my_class_id,
            'section_id' => $section->id,
            'session' => NumlSeedData::SESSION,
            'adm_no' => sprintf('2023-%s-%d%s-%03d', $department, $semester, $section->name, $number),
            'year_admitted' => '2023',
            'grad' => 0,
        ]);
    }
}
