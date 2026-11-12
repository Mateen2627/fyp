<?php
namespace Database\Seeders;

use App\Models\Lga;
use App\Models\State;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LgasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('lgas')->delete();

        // Districts grouped by province name.
        // state_id is resolved from the states table by name, so no hardcoded IDs.
        $districts = [

            'Punjab' => [
                'Attock', 'Bahawalnagar', 'Bahawalpur', 'Bhakkar', 'Chakwal',
                'Chiniot', 'Dera Ghazi Khan', 'Faisalabad', 'Gujranwala',
                'Gujrat', 'Hafizabad', 'Jhang', 'Jhelum', 'Kasur',
                'Khanewal', 'Khushab', 'Lahore', 'Layyah', 'Lodhran',
                'Mandi Bahauddin', 'Mianwali', 'Multan', 'Muzaffargarh',
                'Nankana Sahib', 'Narowal', 'Okara', 'Pakpattan', 'Rahim Yar Khan',
                'Rajanpur', 'Rawalpindi', 'Sahiwal', 'Sargodha', 'Sheikhupura',
                'Sialkot', 'Toba Tek Singh', 'Vehari',
            ],

            'Sindh' => [
                'Badin', 'Dadu', 'Ghotki', 'Hyderabad', 'Jacobabad',
                'Jamshoro', 'Karachi Central', 'Karachi East', 'Karachi South',
                'Karachi West', 'Korangi', 'Larkana', 'Malir', 'Matiari',
                'Mirpur Khas', 'Naushahro Feroze', 'Qambar Shahdadkot',
                'Sanghar', 'Shaheed Benazirabad', 'Shikarpur', 'Sujawal',
                'Sukkur', 'Tando Allahyar', 'Tando Muhammad Khan', 'Tharparkar',
                'Thatta', 'Umerkot', 'Kemari', 'Keamari',
            ],

            'Khyber Pakhtunkhwa' => [
                'Abbottabad', 'Bajaur', 'Bannu', 'Battagram', 'Buner',
                'Charsadda', 'Chitral Lower', 'Chitral Upper', 'Dera Ismail Khan',
                'Hangu', 'Haripur', 'Karak', 'Khyber', 'Kohat',
                'Kohistan Lower', 'Kohistan Upper', 'Kolai Palas', 'Kurram',
                'Lakki Marwat', 'Lower Dir', 'Malakand', 'Mansehra', 'Mardan',
                'Mohmand', 'North Waziristan', 'Nowshera', 'Orakzai',
                'Peshawar', 'Shangla', 'South Waziristan', 'Swabi', 'Swat',
                'Tank', 'Tor Ghar', 'Upper Dir',
            ],

            'Balochistan' => [
                'Awaran', 'Barkhan', 'Chagai', 'Chaman', 'Dera Bugti',
                'Duki', 'Gwadar', 'Harnai', 'Jafarabad', 'Jhal Magsi',
                'Kachi (Bolan)', 'Kalat', 'Kech (Turbat)', 'Kharan',
                'Khuzdar', 'Kohlu', 'Lasbela', 'Lehri', 'Loralai',
                'Mastung', 'Musakhel', 'Nasirabad', 'Nushki', 'Panjgur',
                'Pishin', 'Quetta', 'Qilla Abdullah', 'Qilla Saifullah',
                'Sibi', 'Sohbatpur', 'Surab', 'Washuk', 'Zhob', 'Ziarat',
            ],

            'Gilgit-Baltistan' => [
                'Astore', 'Diamer', 'Ghanche', 'Ghizer', 'Gilgit',
                'Hunza', 'Kharmang', 'Nagar', 'Shigar', 'Skardu',
            ],

            'Azad Jammu & Kashmir' => [
                'Bagh', 'Bhimber', 'Hattian Bala', 'Haveli', 'Kotli',
                'Mirpur', 'Muzaffarabad', 'Neelum', 'Poonch', 'Sudhanoti',
            ],

            'Islamabad Capital Territory' => [
                'Islamabad',
            ],
        ];

        $stateIds = State::pluck('id', 'name')->all();

        foreach ($districts as $stateName => $districtNames) {
            if (!isset($stateIds[$stateName])) {
                throw new \RuntimeException("State '{$stateName}' not found. Run StatesTableSeeder first.");
            }

            $stateId = $stateIds[$stateName];

            foreach ($districtNames as $districtName) {
                Lga::create([
                    'state_id' => $stateId,
                    'name'     => $districtName,
                ]);
            }
        }
    }
}