<?php

namespace Database\Seeders;

use App\Models\ClassType;
use App\Models\MyClass;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class MyClassesTableSeeder extends Seeder
{
    public function run()
    {
        foreach (NumlSeedData::departments() as $code => $name) {
            $department = ClassType::where('code', $code)->firstOrFail();
            for ($semester = 1; $semester <= 8; $semester++) {
                MyClass::firstOrCreate([
                    'class_type_id' => $department->id,
                    'name' => NumlSeedData::className($code, $semester),
                ]);
            }
        }
    }
}
