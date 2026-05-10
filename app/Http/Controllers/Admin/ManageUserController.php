<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
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
        return view('pages.admin.modules.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|max:200|unique:users,username',
            'user_type' => 'required|string|in:super_admin,admin,teacher,accountant,librarian,student,parent',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['code'] = strtoupper(bin2hex(random_bytes(5)));
        $data['photo'] = 'https://via.placeholder.com/150';

        User::create($data);

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
