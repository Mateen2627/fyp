<?php

namespace Database\Seeders;

use App\Models\Setting;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'current_session' => NumlSeedData::SESSION,
            'system_title' => 'NUML',
            'system_name' => 'National University of Modern Languages',
            'address' => 'Islamabad, Pakistan',
            'numl_seed_note' => 'FYP data with specified CS/AI/IT/SE leaders. Other appointments and unsupplied curricula are samples.',
        ];
        foreach ($settings as $type => $description) {
            Setting::updateOrCreate(['type' => $type], ['description' => $description]);
        }

        // Preserve configured contact details, dates and operational settings.
        $defaults = [
            'term_ends' => '', 'term_begins' => '', 'phone' => '',
            'system_email' => '', 'alt_email' => '', 'email_host' => '', 'email_pass' => '',
            'lock_exam' => '0', 'logo' => '',
        ];
        foreach (NumlSeedData::departments() as $code => $name) {
            $defaults['next_term_fees_'.strtolower($code)] = '0';
        }
        foreach ($defaults as $type => $description) {
            Setting::firstOrCreate(['type' => $type], ['description' => $description]);
        }
    }
}
