<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DormitoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Backwards-compatible entry point; the actual schema uses dorms.
        $this->call(DormsTableSeeder::class);
    }
}
