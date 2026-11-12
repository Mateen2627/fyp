<?php
namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    public function run()
    {
        $states = [
            'Punjab',
            'Sindh',
            'Khyber Pakhtunkhwa',
            'Balochistan',
            'Gilgit-Baltistan',
            'Azad Jammu & Kashmir',
            'Islamabad Capital Territory',
        ];

        foreach ($states as $name) {
            State::firstOrCreate(['name' => $name]);
        }
    }
}
