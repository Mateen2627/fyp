<?php

namespace Database\Seeders;

use App\Models\Lga;
use App\Models\Nationality;
use App\Models\StaffRecord;
use App\Models\State;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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

        // Super admin comes first on an empty database for the legacy ID-1 check.
        $accounts = [
            ['NUML-SUPER-ADMIN', 'CJ', 'super_admin'],
            ['NUML-ADMIN', 'Admin', 'admin'],
            ['NUML-ACCOUNTANT', 'Hamza Akram', 'accountant'],
        ];
        foreach (NumlSeedData::faculty() as $department => $names) {
            foreach ($names as $index => $name) {
                $accounts[] = [NumlSeedData::staffCode($department, $index), $name, 'teacher'];
            }
        }

        foreach ($accounts as list($code, $name, $type)) {
            $user = NumlSeedData::seedUser($code, $name, $type, $password, $attributes);
            StaffRecord::updateOrCreate(['user_id' => $user->id], [
                'code' => $code,
                'emp_date' => '2023-09-01',
            ]);
        }
    }
}
