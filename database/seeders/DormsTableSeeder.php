<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DormsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['name' => 'Boys Hostel (Demo)'],
            ['name' => 'Girls Hostel (Demo)'],
        ];
        foreach ($data as $dorm) {
            DB::table('dorms')->updateOrInsert(['name' => $dorm['name']], $dorm);
        }
    }
}
