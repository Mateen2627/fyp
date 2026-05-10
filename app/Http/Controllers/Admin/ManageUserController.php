<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\MyClass;
use App\Models\StudentRecord;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('user_type', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $roles = User::select('user_type')->distinct()->orderBy('user_type')->pluck('user_type');
        $records = $query->latest()->paginate(20)->appends($request->query());

        return view('pages.admin.modules.users.index', compact('records', 'roles'));
    }

    public function create()
    {
        return view('pages.admin.modules.users.create', [
            'classes' => MyClass::with('section')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|max:200|unique:users,username',
            'user_type' => 'required|string|in:super_admin,admin,teacher,accountant,librarian,student,parent',
            'password' => 'required|string|min:6|confirmed',
        ];

        if ($request->user_type === 'student') {
            $rules = array_merge($rules, [
                'my_class_id' => 'required|integer|exists:my_classes,id',
                'section_id' => 'required|integer|exists:sections,id',
                'year_admitted' => 'required|integer|min:2000|max:'.date('Y'),
                'adm_no' => 'nullable|string|max:30|unique:student_records,adm_no',
                'parent_name' => 'required|string|max:200',
                'parent_email' => 'required|email|unique:users,email',
                'parent_username' => 'nullable|string|max:200|unique:users,username',
                'parent_password' => 'required|string|min:6|confirmed',
            ]);
        }

        $data = $request->validate($rules);

        DB::transaction(function () use ($data) {
            $userData = collect($data)->only(['name', 'email', 'username', 'user_type'])->all();
            $userData['name'] = ucwords($userData['name']);
            $userData['username'] = $userData['username'] ?: null;
            $userData['password'] = Hash::make($data['password']);
            $userData['code'] = strtoupper(bin2hex(random_bytes(5)));
            $userData['photo'] = Qs::getDefaultUserImage();

            $student = User::create($userData);

            if ($data['user_type'] !== 'student') {
                return;
            }

            $parent = User::create([
                'name' => ucwords($data['parent_name']),
                'email' => $data['parent_email'],
                'username' => $data['parent_username'] ?: null,
                'user_type' => 'parent',
                'password' => Hash::make($data['parent_password']),
                'code' => strtoupper(bin2hex(random_bytes(5))),
                'photo' => Qs::getDefaultUserImage(),
            ]);

            StudentRecord::create([
                'user_id' => $student->id,
                'my_class_id' => $data['my_class_id'],
                'section_id' => $data['section_id'],
                'my_parent_id' => $parent->id,
                'adm_no' => $data['adm_no'] ?: ($student->username ?: 'STU-'.$student->id),
                'session' => Qs::getSetting('current_session'),
                'year_admitted' => $data['year_admitted'],
            ]);
        });

        return redirect()->route('admin.users.index')->with('flash_success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('pages.admin.modules.users.edit', ['record' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'username' => 'nullable|string|max:200|unique:users,username,'.$user->id,
            'user_type' => 'required|string|in:super_admin,admin,teacher,accountant,librarian,student,parent',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('flash_success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('flash_success', 'User deleted successfully.');
    }
}
