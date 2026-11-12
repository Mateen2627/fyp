<?php

// Standalone integration check; it never connects to the configured application DB.
require __DIR__.'/../vendor/autoload.php';

use App\Models\Course;
use App\Models\Section;
use App\Models\Setting;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\User;
use Database\Seeders\Support\NumlSeedData;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

putenv('APP_ENV=testing');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=array');

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
config([
    'database.default' => 'sqlite',
    'database.connections.sqlite.database' => ':memory:',
    'database.connections.sqlite.foreign_key_constraints' => true,
    'hashing.bcrypt.rounds' => 4,
    'session.driver' => 'array',
]);
DB::purge();

function checkSeed($condition, $message)
{
    if (!$condition) {
        throw new RuntimeException($message);
    }
}

function snapshotSeed()
{
    $snapshot = [];
    foreach (DB::select("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name") as $table) {
        $name = $table->name;
        if ($name !== 'sqlite_sequence') {
            $rows = DB::table($name)->get()->map(function ($row) {
                $values = (array) $row;
                // Seeded passwords are intentionally rehashed, and timestamps may change.
                unset($values['password'], $values['created_at'], $values['updated_at']);
                return $values;
            })->all();
            usort($rows, function ($left, $right) {
                return strcmp(json_encode($left), json_encode($right));
            });
            $snapshot[$name] = $rows;
        }
    }
    return $snapshot;
}

try {
    checkSeed(Artisan::call('migrate', ['--force' => true]) === 0, Artisan::output());
    DB::statement('PRAGMA foreign_keys = ON');
    checkSeed(Artisan::call('db:seed', ['--force' => true]) === 0, Artisan::output());

    checkSeed(DB::table('class_types')->count() === 7, 'Expected seven departments.');
    checkSeed(DB::table('my_classes')->count() === 56, 'Expected 56 semester classes.');
    checkSeed(Section::count() === 112, 'Expected 112 sections.');
    checkSeed(StudentRecord::count() === 1120, 'Expected 1,120 student records.');
    checkSeed(User::where('user_type', 'student')->count() === 1120, 'Student account count does not match.');
    checkSeed(User::where('user_type', 'teacher')->count() === 30, 'Expected 30 teachers.');
    checkSeed(User::findOrFail(1)->user_type === 'super_admin', 'Legacy ID-1 super admin compatibility failed.');
    checkSeed(DB::table('staff_records')->count() === 33, 'Expected staff profiles for all 33 staff accounts.');
    checkSeed(Setting::where('type', 'current_session')->value('description') === '2023-2027', 'Wrong session.');

    $csCounts = [1 => 9, 2 => 8, 3 => 7, 4 => 8, 5 => 8, 6 => 8];
    foreach (NumlSeedData::classes() as $class) {
        list($department, $semester) = NumlSeedData::classDetails($class);
        $subjects = Subject::where('my_class_id', $class->id)->get();
        checkSeed($subjects->count() > 0, 'A semester is missing its subjects.');
        if ($department === 'CS' && $semester <= 6) {
            checkSeed($subjects->count() === $csCounts[$semester], 'Supplied CS subject count is wrong.');
        }
        foreach (Section::where('my_class_id', $class->id)->get() as $section) {
            checkSeed($section->active === 1 || (int) $section->active === 1, 'Inactive section.');
            checkSeed(StudentRecord::where('section_id', $section->id)->count() === 10, 'A section must have ten students.');
            checkSeed($section->teacher->user_type === 'teacher', 'Class incharge is not a teacher.');
            checkSeed(TeacherAssignment::where('teacher_id', $section->teacher_id)->exists(), 'Class incharge has no teaching assignment.');
        }
        foreach ($subjects as $subject) {
            checkSeed(strlen($subject->name) <= 100, 'Subject exceeds the MySQL name limit.');
            $course = Course::where('my_class_id', $class->id)->where('code', $subject->slug)->first();
            checkSeed($course && $course->title === $subject->name, 'Course and subject catalogs differ.');
            checkSeed(TeacherAssignment::where([
                'course_id' => $course->id, 'my_class_id' => $class->id, 'teacher_id' => $subject->teacher_id,
            ])->exists(), 'Course is missing the matching teacher assignment.');
        }
    }

    checkSeed(Subject::count() === Course::count(), 'Course/subject totals differ.');
    checkSeed(Course::count() === TeacherAssignment::count(), 'Course/assignment totals differ.');
    checkSeed(Subject::where('name', 'like', '%(Non-Credited)%')->count() === 2, 'Missing non-credited subjects.');
    checkSeed(Subject::where('name', 'like', '%(Lab)%')->count() > 0, 'Missing lab subjects.');
    checkSeed(DB::table('student_records as sr')->join('sections as s', 's.id', '=', 'sr.section_id')
        ->whereColumn('sr.my_class_id', '<>', 's.my_class_id')->count() === 0, 'Student/section class mismatch.');
    checkSeed(StudentRecord::where('session', '<>', '2023-2027')->count() === 0, 'Student session mismatch.');

    foreach (NumlSeedData::departments() as $code => $name) {
        foreach (['hod', 'coordinator'] as $role) {
            $appointment = json_decode(Setting::where('type', 'numl_'.$role.'_'.strtolower($code))->value('description'), true);
            checkSeed($appointment && $appointment['sample'], 'Missing sample staff appointment.');
            checkSeed(User::findOrFail($appointment['user_id'])->user_type === 'teacher', 'Department role removed teaching access.');
            checkSeed(TeacherAssignment::where('teacher_id', $appointment['user_id'])->exists(), 'Department leader has no teaching assignment.');
        }
    }

    $logins = ['cj', 'admin', 'faisal', 'rao', 'neelofer', 'kashif', 'zainulabideen', 'mustajeeb', 'marjan', 'mateen', 'mudassir', 'sheeza', 'asad', 'faiq'];
    foreach ($logins as $username) {
        checkSeed(Auth::attempt(['username' => $username, 'password' => 'cj']), 'Login failed for '.$username);
        Auth::logout();
    }
    foreach (NumlSeedData::namedStudents() as $index => $name) {
        $student = User::where('name', $name)->firstOrFail()->student_record;
        checkSeed($student->my_class->name === 'CS - Semester 6' && $student->section->name === 'A', 'Named student placement is wrong.');
        checkSeed(TeacherAssignment::where('my_class_id', $student->my_class_id)->count() === 8, 'Named student course screen would be empty.');
    }
    foreach (User::all() as $user) {
        checkSeed(Hash::check('cj', $user->password), 'A seeded account has the wrong password.');
    }
    checkSeed(User::where('username', 'ali2')->exists(), 'Duplicate first names need numbered usernames.');

    // Preserve unrelated records and existing contact settings across a rerun.
    $existing = User::create([
        'code' => 'EXISTING-USER', 'name' => 'Existing Person', 'username' => 'existing',
        'user_type' => 'student', 'password' => Hash::make('keep-me'),
    ]);
    StudentRecord::create([
        'user_id' => $existing->id, 'my_class_id' => Section::first()->my_class_id,
        'section_id' => Section::first()->id, 'session' => '2022-2026', 'adm_no' => 'EXISTING-ADM',
    ]);
    Setting::where('type', 'phone')->update(['description' => 'custom-contact']);
    $before = snapshotSeed();
    checkSeed(Artisan::call('db:seed', ['--force' => true]) === 0, Artisan::output());
    checkSeed(snapshotSeed() === $before, 'Seeding twice changed IDs, usernames, relationships, or record counts.');
    checkSeed(Hash::check('keep-me', $existing->fresh()->password), 'Existing user password was changed.');
    checkSeed(DB::select('PRAGMA foreign_key_check') === [], 'Foreign-key integrity failed.');

    // First-name collision with a pre-existing account must not overwrite it.
    DB::beginTransaction();
    $collision = NumlSeedData::seedUser('NUML-TEST-COLLISION', 'Existing Demo', 'student', Hash::make('cj'));
    checkSeed($collision->username === 'existing2', 'An occupied username was not given a suffix.');
    $again = NumlSeedData::seedUser('NUML-TEST-COLLISION', 'Existing Demo', 'student', Hash::make('cj'));
    checkSeed($again->id === $collision->id && $again->username === 'existing2', 'Collision handling is not repeatable.');
    DB::rollBack();

    echo 'PASS: fresh migration, two seed runs, 7 departments, 56 classes, 112 sections, 1,120 students, '.Course::count()." courses, credentials, appointments, and existing-data preservation.\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'FAIL: '.$error->getMessage()."\n".$error->getTraceAsString()."\n");
    exit(1);
}
