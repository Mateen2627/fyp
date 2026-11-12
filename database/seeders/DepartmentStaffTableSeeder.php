<?php

namespace Database\Seeders;

use App\Models\ClassType;
use App\Models\Setting;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class DepartmentStaffTableSeeder extends Seeder
{
    public function run()
    {
        // There is no department-role table. Settings record appointments only;
        // user_type stays teacher, preserving existing teaching access.
        // These appointments do not grant new permissions.
        foreach (NumlSeedData::departments() as $code => $name) {
            $department = ClassType::where('code', $code)->firstOrFail();
            $teachers = NumlSeedData::teachers($code);
            foreach (['hod', 'coordinator'] as $index => $role) {
                Setting::updateOrCreate(['type' => 'numl_'.$role.'_'.strtolower($code)], [
                    'description' => json_encode([
                        'department_id' => $department->id,
                        'user_id' => $teachers[$index]->id,
                        'session' => NumlSeedData::SESSION,
                        'sample' => true,
                    ]),
                ]);
            }
        }
    }
}
