<?php

namespace Database\Seeders;

use App\Models\ClassType;
use App\Models\DepartmentStaff;
use App\Models\Setting;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class DepartmentStaffTableSeeder extends Seeder
{
    public function run()
    {
        $heads = ['CS' => ['CS', 2], 'AI' => ['CS', 2], 'IT' => ['CS', 1], 'SE' => ['CS', 1]];
        $coordinators = ['CS' => ['CS', 4], 'AI' => ['CS', 4], 'IT' => ['CS', 5], 'SE' => ['CS', 5]];
        foreach (NumlSeedData::departments() as $code => $name) {
            $department = ClassType::where('code', $code)->firstOrFail();
            $teachers = NumlSeedData::teachers($code);
            foreach ($teachers as $teacher) {
                DepartmentStaff::firstOrCreate(['department_id' => $department->id, 'user_id' => $teacher->id]);
            }
            foreach (['hod', 'coordinator'] as $index => $role) {
                $mapping = $role === 'hod' ? $heads : $coordinators;
                $teacher = isset($mapping[$code])
                    ? \App\User::where('code', NumlSeedData::staffCode($mapping[$code][0], $mapping[$code][1]))->firstOrFail() : $teachers[$index];
                DepartmentStaff::where('department_id', $department->id)->update(['is_'.$role => false]);
                DepartmentStaff::updateOrCreate(['department_id' => $department->id, 'user_id' => $teacher->id], ['is_'.$role => true]);
                Setting::updateOrCreate(['type' => 'numl_'.$role.'_'.strtolower($code)], [
                    'description' => json_encode([
                        'department_id' => $department->id,
                        'user_id' => $teacher->id,
                        'session' => NumlSeedData::SESSION,
                        'sample' => !isset($mapping[$code]),
                    ]),
                ]);
            }
        }
    }
}
