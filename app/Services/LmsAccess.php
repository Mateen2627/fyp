<?php

namespace App\Services;

use App\Models\MyClass;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use App\User;
use Illuminate\Support\Facades\Auth;

class LmsAccess
{
    public static function departmentIds($role = null, User $user = null)
    {
        $user = $user ?: Auth::user();
        if (!$user || $user->user_type !== 'teacher') {
            return collect();
        }
        $query = $user->departmentRoles();
        if ($role === 'hod' || $role === 'coordinator') {
            $query->where('is_'.$role, true);
        } elseif ($role === 'leader') {
            $query->where(function ($q) {
                $q->where('is_hod', true)->orWhere('is_coordinator', true);
            });
        }
        return $query->pluck('department_id');
    }

    public static function assertClass($classId, $role)
    {
        $class = MyClass::findOrFail($classId);
        abort_unless(self::departmentIds($role)->contains($class->class_type_id), 403);
        return $class;
    }

    public static function teachingQuery(User $user = null)
    {
        $user = $user ?: Auth::user();
        return TeacherAssignment::where('teacher_id', $user ? $user->id : 0)
            ->whereHas('course', function ($q) { $q->whereColumn('courses.my_class_id', 'teacher_assignments.my_class_id'); })->whereHas('my_class');
    }

    public static function assertTeaching($courseId, $classId, $sectionId = null)
    {
        $query = self::teachingQuery()->where('course_id', $courseId)->where('my_class_id', $classId);
        $query->where(function ($q) use ($sectionId) {
            $q->whereNull('section_id');
            if ($sectionId) {
                $q->orWhere('section_id', $sectionId);
            }
        });
        abort_unless(Auth::user()->user_type === 'teacher' && $query->exists(), 403);
    }

    public static function assertActivityOwner($activity)
    {
        abort_unless((int) $activity->teacher_id === (int) Auth::id(), 403);
        self::assertTeaching($activity->course_id, $activity->my_class_id, $activity->section_id);
    }

    public static function scopeTeacherActivities($query)
    {
        $table = $query->getModel()->getTable();
        return $query->where($table.'.teacher_id', Auth::id())->whereExists(function ($q) use ($table) {
            $q->selectRaw('1')->from('teacher_assignments')->where('teacher_assignments.teacher_id', Auth::id())
                ->whereColumn('teacher_assignments.course_id', $table.'.course_id')
                ->whereColumn('teacher_assignments.my_class_id', $table.'.my_class_id')
                ->where(function ($sections) use ($table) {
                    $sections->whereNull('teacher_assignments.section_id')->orWhereColumn('teacher_assignments.section_id', $table.'.section_id');
                });
        });
    }

    public static function scopeTeachingRecords($query)
    {
        $pairs = self::teachingQuery()->get();
        return $query->where(function ($q) use ($pairs) {
            $q->whereRaw('1 = 0');
            foreach ($pairs as $pair) {
                $q->orWhere(function ($entry) use ($pair) {
                    $entry->where('course_id', $pair->course_id)->where('my_class_id', $pair->my_class_id);
                    if ($pair->section_id) { $entry->where('section_id', $pair->section_id); }
                });
            }
        });
    }

    public static function studentRecord()
    {
        return StudentRecord::where('user_id', Auth::id())->where('grad', 0)->latest('id')->firstOrFail();
    }

    public static function scopeStudentActivities($query, StudentRecord $record)
    {
        return $query->where('my_class_id', $record->my_class_id)->whereNotNull('teacher_id')
            ->whereHas('course', function ($q) use ($record) {
                $q->where('my_class_id', $record->my_class_id);
            })->where(function ($q) use ($record) {
                $q->whereNull('section_id')->orWhere('section_id', $record->section_id);
            });
    }

    public static function assertStudentActivity($activity)
    {
        $record = self::studentRecord();
        abort_unless(self::scopeStudentActivities($activity->newQuery(), $record)->whereKey($activity->id)->exists(), 403);
        return $record;
    }

    public static function visibleClassIds()
    {
        $user = Auth::user();
        if (in_array($user->user_type, ['admin', 'super_admin'], true)) {
            return MyClass::pluck('id');
        }
        if ($user->user_type === 'student') {
            return StudentRecord::where('user_id', $user->id)->pluck('my_class_id');
        }
        return MyClass::whereIn('class_type_id', self::departmentIds('leader'))->pluck('id')
            ->merge(self::teachingQuery()->pluck('my_class_id'))->unique();
    }
}
