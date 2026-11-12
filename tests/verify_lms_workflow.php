<?php

// Uses the seed verification's isolated, fully migrated in-memory database.
require __DIR__.'/verify_numl_seed.php';

use App\Models\Assignment;
use App\Models\AttendanceCheckIn;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentAttendance;
use App\Models\StudentQuizAttempt;
use App\Models\TeacherAssignment;
use App\Models\TimeSlot;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use App\Services\FaceVerificationService;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

$assertions = 0;
$temporaryRoot = sys_get_temp_dir().'/numl-lms-'.bin2hex(random_bytes(6));
config(['filesystems.disks.local.root' => $temporaryRoot.'/private', 'filesystems.disks.public.root' => $temporaryRoot.'/public']);
Storage::forgetDisk(['local', 'public']);
Carbon::setTestNow(Carbon::parse('2026-11-12 09:15:00', 'Asia/Karachi'));

function lmsRequest($user, $method, $uri, array $data = [], $expected = 200, array $files = [])
{
    global $assertions;
    Auth::forgetGuards();
    if ($user) { Auth::guard()->setUser($user->fresh()); }
    $request = Request::create($uri, $method, $data, [], $files, ['HTTP_ACCEPT' => $method === 'GET' ? 'text/html' : 'application/json']);
    $kernel = app(Kernel::class);
    $response = $kernel->handle($request);
    $kernel->terminate($request, $response);
    $assertions++;
    if ($response->getStatusCode() !== $expected) {
        throw new RuntimeException($method.' '.$uri.' expected '.$expected.', got '.$response->getStatusCode().': '.substr((string) $response->getContent(), 0, 3500));
    }
    return $response;
}

try {
    $admin = User::where('username', 'admin')->firstOrFail();
    $hod = User::where('username', 'neelofer')->firstOrFail();
    $rao = User::where('username', 'rao')->firstOrFail();
    $coordinator = User::where('username', 'zainulabideen')->firstOrFail();
    $mustajeeb = User::where('username', 'mustajeeb')->firstOrFail();
    $teacher = User::where('username', 'faisal')->firstOrFail();
    $otherTeacher = User::where('username', 'kashif')->firstOrFail();
    $outsideTeacher = User::where('username', 'salman')->firstOrFail();
    $student = User::where('username', 'marjan')->firstOrFail();
    $record = $student->student_record;
    $class = $record->my_class;
    $section = $record->section;
    $sectionB = Section::where('my_class_id', $class->id)->where('name', 'B')->firstOrFail();
    $studentB = $sectionB->student_record()->firstOrFail()->user;
    $otherClass = MyClass::where('name', 'IT - Semester 6')->firstOrFail();
    $course = Course::where('my_class_id', $class->id)->where('title', 'Automata Theory')->firstOrFail();
    $teaching = TeacherAssignment::where('course_id', $course->id)->firstOrFail();

    checkSeed($hod->departmentRoles()->where('is_hod', true)->with('department')->get()->pluck('department.code')->sort()->values()->all() === ['AI', 'CS'], 'Neelofer must head CS/AI.');
    checkSeed($rao->departmentRoles()->where('is_hod', true)->with('department')->get()->pluck('department.code')->sort()->values()->all() === ['IT', 'SE'], 'Rao must head IT/SE.');
    checkSeed($coordinator->departmentRoles()->where('is_coordinator', true)->count() === 2, 'Zainulabideen coordinator mapping is wrong.');
    checkSeed($mustajeeb->departmentRoles()->where('is_coordinator', true)->count() === 2, 'Mustajeeb coordinator mapping is wrong.');

    // Render the actual screens, including role-specific navigation.
    lmsRequest(null, 'GET', '/login');
    lmsRequest(null, 'GET', '/');
    lmsRequest(null, 'GET', '/register', [], 404);
    lmsRequest($admin, 'GET', '/admin/courses/create');
    foreach ([[$admin, '/admin/dashboard'], [$admin, '/admin/users/create'], [$admin, '/admin/users/'.$student->id.'/edit'], [$admin, '/admin/classes/create'], [$admin, '/admin/classes/'.$class->id.'/edit'], [$hod, '/academic/progress'], [$coordinator, '/academic/progress'], [$hod, '/hod/assign-teachers'], [$hod, '/hod/assign-teachers/create'], [$teacher, '/teacher/dashboard'], [$student, '/student/dashboard'], [$student, '/student/attendance'], [$teacher, '/teacher/attendance'], [$coordinator, '/timetables']] as $page) {
        lmsRequest($page[0], 'GET', $page[1]);
    }
    lmsRequest($admin, 'POST', '/hod/assign-teachers', [], 403);
    lmsRequest($teacher, 'POST', '/hod/assign-teachers', [], 403);
    lmsRequest($coordinator, 'POST', '/hod/assign-teachers', [], 403);
    lmsRequest($student, 'POST', '/admin/users', [], 403);
    lmsRequest($teacher, 'POST', '/admin/users', [], 403);

    $allocation = ['course_id' => $course->id, 'my_class_id' => $class->id, 'teacher_id' => $teacher->id, 'section_id' => $section->id];
    lmsRequest($rao, 'PUT', '/hod/assign-teachers/'.$teaching->id, $allocation, 403);
    lmsRequest($hod, 'PUT', '/hod/assign-teachers/'.$teaching->id, array_merge($allocation, ['teacher_id' => $outsideTeacher->id]), 422);
    lmsRequest($hod, 'PUT', '/hod/assign-teachers/'.$teaching->id, $allocation, 302);
    lmsRequest($hod, 'POST', '/hod/assign-teachers', $allocation, 422);
    lmsRequest($hod, 'POST', '/hod/assign-teachers', array_merge($allocation, ['section_id' => $sectionB->id, 'teacher_id' => $otherTeacher->id]), 302);
    $teachingB = TeacherAssignment::where('course_id', $course->id)->where('section_id', $sectionB->id)->firstOrFail();

    $timetable = ['name' => 'CS 6 Section A Test', 'my_class_id' => $class->id, 'section_id' => $section->id];
    foreach ([$admin, $hod, $teacher, $student] as $denied) { lmsRequest($denied, 'POST', '/timetables/records', $timetable, 403); }
    lmsRequest($mustajeeb, 'POST', '/timetables/records', $timetable, 403);
    lmsRequest($coordinator, 'POST', '/timetables/records', $timetable, 302);
    $ttr = TimeTableRecord::where('name', $timetable['name'])->firstOrFail();
    $slot = ['ttr_id' => $ttr->id, 'hour_from' => 9, 'min_from' => '00', 'meridian_from' => 'AM', 'hour_to' => 10, 'min_to' => '00', 'meridian_to' => 'AM'];
    lmsRequest($coordinator, 'POST', '/timetables/time_slots', $slot, 302);
    $timeSlot = TimeSlot::where('ttr_id', $ttr->id)->firstOrFail();
    $lessonData = ['ttr_id' => $ttr->id, 'ts_id' => $timeSlot->id, 'subject_id' => $course->subject_id, 'day' => 'Thursday'];
    lmsRequest($coordinator, 'POST', '/timetables', $lessonData, 302);
    $lesson = TimeTable::where('ttr_id', $ttr->id)->firstOrFail();
    lmsRequest($coordinator, 'POST', '/timetables', $lessonData, 422);
    lmsRequest($mustajeeb, 'DELETE', '/timetables/records/'.$ttr->id, [], 403);
    lmsRequest($hod, 'DELETE', '/timetables/records/'.$ttr->id, [], 403);
    lmsRequest($coordinator, 'GET', '/timetables/records/manage/'.$ttr->id);
    lmsRequest($coordinator, 'GET', '/timetables/records/edit/'.$ttr->id);
    lmsRequest($student, 'GET', '/timetables/records/show/'.$ttr->id);
    lmsRequest($studentB, 'GET', '/timetables/records/show/'.$ttr->id, [], 403);
    lmsRequest($coordinator, 'POST', '/timetables/records', array_merge($timetable, ['name' => 'CS 6 Section B Test', 'section_id' => $sectionB->id]), 302);
    $ttrB = TimeTableRecord::where('section_id', $sectionB->id)->firstOrFail();
    lmsRequest($coordinator, 'POST', '/timetables/time_slots', array_merge($slot, ['ttr_id' => $ttrB->id]), 302);
    $slotB = TimeSlot::where('ttr_id', $ttrB->id)->firstOrFail();
    lmsRequest($coordinator, 'POST', '/timetables', array_merge($lessonData, ['ttr_id' => $ttrB->id, 'ts_id' => $slotB->id]), 302);
    lmsRequest($hod, 'PUT', '/hod/assign-teachers/'.$teachingB->id, array_merge($allocation, ['section_id' => $sectionB->id]), 422);
    checkSeed($teachingB->fresh()->teacher_id === $otherTeacher->id, 'Clashing reassignment must roll back.');

    // Subject and section assignment controls creation, visibility, submission and grading.
    $activityData = ['title' => 'Automata Homework', 'course_id' => $course->id, 'my_class_id' => $class->id, 'section_id' => $section->id, 'total_marks' => 50, 'due_date' => '2026-11-13'];
    lmsRequest($otherTeacher, 'POST', '/teacher/assignments', $activityData, 403);
    lmsRequest($teacher, 'POST', '/teacher/assignments', array_merge($activityData, ['my_class_id' => $otherClass->id]), 422);
    lmsRequest($teacher, 'POST', '/teacher/assignments', $activityData, 302);
    $assignment = Assignment::where('title', 'Automata Homework')->firstOrFail();
    lmsRequest($studentB, 'GET', '/student/assignments/'.$assignment->id, [], 403);
    lmsRequest($studentB, 'POST', '/student/assignments/'.$assignment->id.'/submit', ['submission_text' => 'Wrong section'], 403);
    lmsRequest($student, 'POST', '/student/assignments/'.$assignment->id.'/submit', ['submission_text' => 'My automata solution'], 302);
    $submission = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->firstOrFail();
    lmsRequest($teacher, 'POST', '/teacher/assignments/'.$assignment->id.'/submissions/'.$submission->id.'/mark', ['marks' => 51], 422);
    lmsRequest($teacher, 'POST', '/teacher/assignments/'.$assignment->id.'/submissions/'.$submission->id.'/mark', ['marks' => 42, 'teacher_feedback' => 'Good work'], 302);
    foreach ([$hod, $coordinator, $otherTeacher] as $denied) { lmsRequest($denied, 'POST', '/teacher/assignments/'.$assignment->id.'/submissions/'.$submission->id.'/mark', ['marks' => 0], 403); }
    checkSeed((float) $submission->fresh()->marks === 42.0, 'Another role modified the subject teacher\'s marks.');
    lmsRequest($teacher, 'GET', '/teacher/assignments/'.$assignment->id);
    lmsRequest($student, 'GET', '/student/assignments/'.$assignment->id);
    lmsRequest($student, 'GET', '/student/assignments');
    lmsRequest($studentB, 'GET', '/student/assignments');
    lmsRequest($teacher, 'GET', '/teacher/assignments/create');
    lmsRequest($teacher, 'GET', '/teacher/assignments/'.$assignment->id.'/edit');

    lmsRequest($teacher, 'POST', '/teacher/quizzes', array_merge($activityData, ['title' => 'Automata Quiz', 'total_marks' => 20, 'scheduled_at' => '2026-11-12 09:00:00']), 302);
    $quiz = Quiz::where('title', 'Automata Quiz')->firstOrFail();
    lmsRequest($studentB, 'POST', '/student/quizzes/'.$quiz->id.'/submit', ['answers' => 'Unauthorized'], 403);
    lmsRequest($student, 'POST', '/student/quizzes/'.$quiz->id.'/submit', ['answers' => 'My quiz answers'], 302);
    $attempt = StudentQuizAttempt::where('quiz_id', $quiz->id)->firstOrFail();
    lmsRequest($teacher, 'POST', '/teacher/quizzes/'.$quiz->id.'/attempts/'.$attempt->id.'/mark', ['marks' => 21], 422);
    lmsRequest($teacher, 'POST', '/teacher/quizzes/'.$quiz->id.'/attempts/'.$attempt->id.'/mark', ['marks' => 15], 302);
    lmsRequest($hod, 'POST', '/teacher/quizzes/'.$quiz->id.'/attempts/'.$attempt->id.'/mark', ['marks' => 0], 403);
    lmsRequest($student, 'GET', '/student/quizzes/'.$quiz->id);
    lmsRequest($teacher, 'GET', '/teacher/quizzes/'.$quiz->id);
    lmsRequest($teacher, 'GET', '/teacher/quizzes/create');
    lmsRequest($teacher, 'GET', '/teacher/quizzes/'.$quiz->id.'/edit');
    lmsRequest($teacher, 'PUT', '/teacher/quizzes/'.$quiz->id, ['title' => $quiz->title, 'total_marks' => 10], 422);
    lmsRequest($teacher, 'PUT', '/teacher/assignments/'.$assignment->id, ['title' => $assignment->title, 'total_marks' => 20], 422);

    $subjectMarkData = ['subject_id' => $course->subject_id, 'section_id' => $section->id, 'academic_year' => '2023-2027', 'mid_marks' => [$student->id => 25], 'final_marks' => [$student->id => 40]];
    lmsRequest($teacher, 'POST', '/teacher/subject-marks', $subjectMarkData, 302);
    lmsRequest($otherTeacher, 'POST', '/teacher/subject-marks', $subjectMarkData, 403);
    lmsRequest($hod, 'POST', '/teacher/subject-marks', $subjectMarkData, 403);
    lmsRequest($otherTeacher, 'POST', '/teacher/subject-marks', array_merge($subjectMarkData, ['section_id' => $sectionB->id, 'mid_marks' => [$studentB->id => 20], 'final_marks' => [$studentB->id => 30]]), 302);
    lmsRequest($teacher, 'GET', '/teacher/subject-marks?subject_id='.$course->subject_id);
    lmsRequest($student, 'GET', '/student/subject-marks');

    $assignment->update(['attachment_path' => 'assignment_materials/test.pdf']);
    Storage::disk('local')->put('assignment_materials/test.pdf', '%PDF-1.4 test fixture');
    lmsRequest($student, 'GET', '/student/assignments/'.$assignment->id.'/view');
    lmsRequest($studentB, 'GET', '/student/assignments/'.$assignment->id.'/view', [], 403);
    lmsRequest(null, 'GET', '/storage/assignment_materials/test.pdf', [], 403);
    $quiz->update(['attachment_path' => 'quiz_materials/legacy.pdf']);
    Storage::disk('public')->put('quiz_materials/legacy.pdf', '%PDF-1.4 legacy fixture');
    checkSeed(Artisan::call('lms:secure-attachments') === 0, Artisan::output());
    checkSeed(Storage::disk('local')->exists('quiz_materials/legacy.pdf') && !Storage::disk('public')->exists('quiz_materials/legacy.pdf'), 'Legacy material remained publicly available.');
    lmsRequest($student, 'GET', '/student/quizzes/'.$quiz->id.'/view');
    foreach ([$hod, $coordinator] as $leader) {
        lmsRequest($leader, 'GET', '/academic/progress/classes/'.$class->id);
        lmsRequest($leader, 'GET', '/academic/progress/assignments/'.$assignment->id);
        lmsRequest($leader, 'GET', '/academic/progress/assignments/'.$assignment->id.'/material');
    }
    lmsRequest($rao, 'GET', '/academic/progress/classes/'.$class->id, [], 403);
    lmsRequest($hod, 'GET', '/academic/progress/classes/'.$otherClass->id, [], 403);
    lmsRequest($hod, 'POST', '/academic/progress/assignments/'.$assignment->id, ['marks' => 0], 405);
    lmsRequest($coordinator, 'DELETE', '/assignments/'.$assignment->id, [], 403);
    lmsRequest($admin, 'POST', '/subjects', [], 403);
    lmsRequest($teacher, 'POST', '/assignments', [], 403);

    // Facial check-in never trusts client-supplied confidence/verified/status/user ID.
    $capture = function () { return ['capture' => UploadedFile::fake()->image('capture.jpg', 120, 120)]; };
    lmsRequest($studentB, 'POST', '/student/attendance/check-in', ['time_table_id' => $lesson->id], 403, $capture());
    lmsRequest($student, 'POST', '/student/attendance/check-in', ['time_table_id' => $lesson->id, 'facial_verified' => true, 'status' => 'verified', 'user_id' => $studentB->id, 'confidence' => 100], 202, $capture());
    checkSeed(AttendanceCheckIn::where('user_id', $student->id)->firstOrFail()->status === 'pending', 'Client forged a verified face check-in.');
    checkSeed(StudentAttendance::where('user_id', $student->id)->count() === 0, 'Pending verification marked a student present.');
    lmsRequest($student, 'POST', '/student/attendance/check-in', ['time_table_id' => $lesson->id], 422, $capture());
    lmsRequest($teacher, 'POST', '/teacher/attendance/facial', ['student_id' => $student->id, 'facial_confidence' => 100], 403);
    app()->instance(FaceVerificationService::class, new class extends FaceVerificationService {
        public function verify(User $student, UploadedFile $capture, TimeTable $lesson) { return ['verified' => true, 'message' => 'Test-only server verifier accepted the capture.']; }
    });
    $secondStudent = User::where('username', 'mateen')->firstOrFail();
    lmsRequest($secondStudent, 'POST', '/student/attendance/check-in', ['time_table_id' => $lesson->id], 200, $capture());
    checkSeed(StudentAttendance::where('user_id', $secondStudent->id)->where('course_id', $course->id)->where('facial_verified', true)->exists(), 'Trusted server verifier did not persist lesson attendance.');
    lmsRequest($teacher, 'GET', '/teacher/attendance');
    StudentAttendance::create(['user_id' => $studentB->id, 'time_table_id' => TimeTable::where('ttr_id', $ttrB->id)->firstOrFail()->id, 'course_id' => $course->id, 'my_class_id' => $class->id, 'section_id' => $sectionB->id, 'attendance_date' => '2026-11-12', 'status' => 'present', 'mode' => 'facial', 'facial_verified' => true]);
    $teacherAttendance = lmsRequest($teacher, 'GET', '/teacher/attendance?course_id='.$course->id);
    checkSeed(strpos($teacherAttendance->getContent(), $studentB->name) === false, 'Teacher saw attendance from another section of the subject.');
    lmsRequest($hod, 'GET', '/academic/progress/classes/'.$class->id);
    lmsRequest($student, 'GET', '/student/attendance');
    Carbon::setTestNow(Carbon::parse('2026-11-12 17:00:00', 'Asia/Karachi'));
    lmsRequest(User::where('username', 'mudassir')->firstOrFail(), 'POST', '/student/attendance/check-in', ['time_table_id' => $lesson->id], 422, $capture());

    // Reassigning a subject transfers its existing work without losing grades.
    lmsRequest($hod, 'PUT', '/hod/assign-teachers/'.$teaching->id, array_merge($allocation, ['teacher_id' => $hod->id]), 302);
    checkSeed($assignment->fresh()->teacher_id === $hod->id && $quiz->fresh()->teacher_id === $hod->id, 'Teacher reassignment stranded existing work.');
    lmsRequest($teacher, 'POST', '/teacher/assignments/'.$assignment->id.'/submissions/'.$submission->id.'/mark', ['marks' => 1], 403);
    lmsRequest($hod, 'POST', '/teacher/assignments/'.$assignment->id.'/submissions/'.$submission->id.'/mark', ['marks' => 43], 302);
    lmsRequest($hod, 'PUT', '/hod/assign-teachers/'.$teaching->id, array_merge($allocation, ['teacher_id' => $hod->id, 'section_id' => null]), 422);

    // Student placement requires matching semester and section; no parent account is mandatory.
    $studentValues = ['name' => $student->name, 'username' => $student->username, 'email' => $student->email, 'user_type' => 'student', 'my_class_id' => $otherClass->id, 'section_id' => $section->id, 'session' => '2023-2027', 'adm_no' => $record->adm_no, 'year_admitted' => 2023];
    lmsRequest($admin, 'PUT', '/admin/users/'.$student->id, $studentValues, 422);
    $targetSection = Section::where('my_class_id', $otherClass->id)->firstOrFail();
    lmsRequest($admin, 'PUT', '/admin/users/'.$student->id, array_merge($studentValues, ['section_id' => $targetSection->id]), 302);
    checkSeed($student->fresh()->student_record->section_id === $targetSection->id, 'Admin could not move the student to another semester/section.');
    lmsRequest($student, 'GET', '/student/assignments/'.$assignment->id, [], 403);
    lmsRequest($admin, 'POST', '/admin/users', ['name' => 'Workflow Teacher', 'username' => 'workflowteacher', 'user_type' => 'teacher', 'department_ids' => [$class->class_type_id]], 302);
    $newTeacher = User::where('username', 'workflowteacher')->firstOrFail();
    checkSeed($newTeacher->departmentRoles()->count() === 1, 'Teacher department membership was not created.');
    lmsRequest($admin, 'DELETE', '/admin/users/'.$newTeacher->id, [], 302);
    checkSeed(User::find($newTeacher->id) === null && User::withTrashed()->findOrFail($newTeacher->id)->trashed(), 'Deleted teacher retained active access.');
    lmsRequest($admin, 'DELETE', '/admin/users/'.$teacher->id, [], 302);
    checkSeed(Artisan::call('db:seed', ['--force' => true]) === 0, Artisan::output());
    checkSeed(User::withTrashed()->findOrFail($teacher->id)->trashed(), 'Seeding restored a deleted faculty account.');
    checkSeed($student->fresh()->student_record->section_id === $targetSection->id, 'Seeding undid the administrator\'s student placement.');
    checkSeed(TeacherAssignment::where('course_id', $course->id)->whereNull('section_id')->count() === 0, 'Seeding restored a revoked class-wide teaching assignment.');
    checkSeed(TeacherAssignment::whereKey($teachingB->id)->value('teacher_id') === $otherTeacher->id, 'Seeding replaced an HOD allocation.');

    echo 'PASS: '.$assertions." HTTP workflow checks covering role permissions, department/section isolation, teacher allocation, timetable clashes, submissions, grading, private files, facial check-in, and administration.\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'FAIL: '.$error->getMessage()."\n".$error->getTraceAsString()."\n");
    $failed = true;
} finally {
    Carbon::setTestNow();
    if (is_dir($temporaryRoot)) {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($temporaryRoot, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($iterator as $entry) { $entry->isDir() ? rmdir($entry->getPathname()) : unlink($entry->getPathname()); }
        rmdir($temporaryRoot);
    }
}

if (!empty($failed)) { exit(1); }
