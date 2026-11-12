<?php

namespace Database\Seeders;

use App\Models\ClassType;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class ClassTypesTableSeeder extends Seeder
{
    public function run()
    {
        // Class types represent departments in the existing schema.
        foreach (NumlSeedData::departments() as $code => $name) {
            ClassType::updateOrCreate(['code' => $code], ['name' => $name]);
        }
    }
}
