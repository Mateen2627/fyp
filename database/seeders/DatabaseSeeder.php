<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Roll back the complete dataset if a dependent seeder fails.
        DB::transaction(function () {
            $this->call([
                BloodGroupsTableSeeder::class,
                NationalitiesTableSeeder::class,
                StatesTableSeeder::class,
                LgasTableSeeder::class,
                UserTypesTableSeeder::class,
                ClassTypesTableSeeder::class,
                GradesTableSeeder::class,
                DormsTableSeeder::class,
                SettingsTableSeeder::class,
                UsersTableSeeder::class,
                DepartmentStaffTableSeeder::class,
                MyClassesTableSeeder::class,
                SectionsTableSeeder::class,
                SubjectsTableSeeder::class,
                CoursesTableSeeder::class,
                TeacherAssignmentsTableSeeder::class,
                StudentRecordsTableSeeder::class,
                SkillsTableSeeder::class,
            ]);
        });

        if ($this->command) {
            $students = \App\User::where('user_type', 'student')->where('code', 'like', 'NUML-%')->count();
            $this->command->info('NUML demo ready: 7 departments, 8 semesters each, sections A/B, '.number_format($students).' active seeded students.');
            $this->command->info('All seeded passwords: cj. First-name usernames; duplicates get 2, 3, etc.');
            $this->command->info('Fresh database logins: cj / admin / faisal / rao / neelofer / marjan / mateen.');
            $this->command->info('See database/seeders/README.md for sample data and existing-data behavior.');
        }
    }
}
