<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\ClassType;
use App\Models\DepartmentStaff;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\StaffRecord;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ManageUserController extends Controller
{
    public function __construct() { $this->middleware('teamSA'); }

    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('role')) { $query->where('user_type', $request->role); }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) { $q->where('name', 'like', '%'.$search.'%')->orWhere('email', 'like', '%'.$search.'%')->orWhere('username', 'like', '%'.$search.'%'); });
        }
        $roles = User::select('user_type')->distinct()->orderBy('user_type')->pluck('user_type');
        $records = $query->latest()->paginate(20)->appends($request->query());
        return view('pages.admin.modules.users.index', compact('records', 'roles'));
    }

    public function create() { return view('pages.admin.modules.users.create', $this->formData()); }

    public function edit(User $user)
    {
        $this->protectAdmin($user);
        return view('pages.admin.modules.users.edit', $this->formData($user));
    }

    public function store(Request $request)
    {
        $this->save($request);
        return redirect()->route('admin.users.index')->with('flash_success', 'User created.');
    }

    public function update(Request $request, User $user)
    {
        $this->protectAdmin($user);
        $this->save($request, $user);
        return redirect()->route('admin.users.index')->with('flash_success', 'User and semester placement updated.');
    }

    public function destroy(User $user)
    {
        $this->protectAdmin($user);
        abort_if((int) $user->id === (int) Auth::id(), 422, 'You cannot delete your own account.');
        if ($user->departmentRoles()->where(function ($q) { $q->where('is_hod', true)->orWhere('is_coordinator', true); })->exists()) {
            throw ValidationException::withMessages(['user' => 'Reassign this teacher\'s HOD/coordinator responsibilities before deleting the account.']);
        }
        DB::transaction(function () use ($user) {
            // Archive the account so historic marks and teacher names remain available.
            // Authentication excludes archived users immediately.
            TeacherAssignment::where('teacher_id', $user->id)->delete();
            $user->departmentRoles()->delete();
            StudentRecord::where('user_id', $user->id)->delete();
            Section::where('teacher_id', $user->id)->update(['teacher_id' => null]);
            $user->delete();
        });
        return back()->with('flash_success', 'User removed from active access and rosters. Academic history is retained.');
    }

    private function save(Request $request, User $user = null)
    {
        $id = $user ? $user->id : null;
        $types = Auth::user()->user_type === 'super_admin'
            ? 'super_admin,admin,teacher,accountant,librarian,student,parent'
            : 'admin,teacher,accountant,librarian,student,parent';
        $rules = [
            'name' => 'required|string|max:200',
            'email' => ['nullable', 'email', 'max:100', Rule::unique('users')->ignore($id)],
            'username' => ['required', 'string', 'max:100', Rule::unique('users')->ignore($id)],
            'user_type' => 'required|in:'.$types,
            'password' => 'nullable|string|min:2|confirmed',
        ];
        if ($request->user_type === 'student') {
            $rules += [
                'my_class_id' => 'required|integer|exists:my_classes,id',
                'section_id' => ['required', 'integer', Rule::exists('sections', 'id')->where('my_class_id', $request->my_class_id)->where('active', 1)],
                'year_admitted' => 'required|integer|min:2000|max:'.date('Y'),
                'session' => ['required', 'regex:/^20[0-9]{2}-20[0-9]{2}$/'],
                'adm_no' => ['required', 'string', 'max:30', Rule::unique('student_records')->ignore($user && $user->student_record ? $user->student_record->id : null)],
            ];
        }
        if ($request->user_type === 'teacher') {
            $rules += ['department_ids' => 'required|array|min:1', 'department_ids.*' => 'required|integer|distinct|exists:class_types,id'];
        }
        $data = $request->validate($rules);
        if ($user && $user->user_type !== $data['user_type']) {
            throw ValidationException::withMessages(['user_type' => 'Existing account roles cannot be changed. Create a separate account to preserve academic history.']);
        }
        DB::transaction(function () use ($data, $user) {
            $values = collect($data)->only(['name', 'email', 'username', 'user_type'])->all();
            if (!$user || !empty($data['password'])) { $values['password'] = Hash::make($data['password'] ?? 'cj'); }
            if ($user) { $user->update($values); }
            else { $user = User::create(array_merge($values, ['code' => strtoupper(Str::random(12))])); }

            if ($user->user_type === 'student') {
                StudentRecord::updateOrCreate(['user_id' => $user->id], collect($data)->only(['my_class_id', 'section_id', 'session', 'adm_no', 'year_admitted'])->all());
            } elseif ($user->user_type === 'teacher') {
                $removed = $user->departmentRoles()->whereNotIn('department_id', $data['department_ids'])->get();
                foreach ($removed as $membership) {
                    $hasTeaching = TeacherAssignment::where('teacher_id', $user->id)->whereHas('my_class', function ($q) use ($membership) { $q->where('class_type_id', $membership->department_id); })->exists();
                    if ($membership->is_hod || $membership->is_coordinator || $hasTeaching) {
                        throw ValidationException::withMessages(['department_ids' => 'Reassign this teacher\'s leadership and teaching duties before removing their department.']);
                    }
                    $membership->delete();
                }
                foreach ($data['department_ids'] as $departmentId) {
                    DepartmentStaff::firstOrCreate(['department_id' => $departmentId, 'user_id' => $user->id]);
                }
                StaffRecord::firstOrCreate(['user_id' => $user->id], ['code' => $user->code, 'emp_date' => now()->toDateString()]);
            }
        });
    }

    private function protectAdmin($user)
    {
        abort_if($user->user_type === 'super_admin' && Auth::user()->user_type !== 'super_admin', 403);
        abort_if($user->user_type === 'super_admin' && (int) $user->id === 1 && (int) Auth::id() !== 1, 403);
    }

    private function formData($user = null)
    {
        return ['record' => $user, 'classes' => MyClass::with('section')->orderBy('name')->get(), 'departments' => ClassType::orderBy('name')->get()];
    }
}
