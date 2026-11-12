<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Services\FaceVerificationService;
use App\Services\LmsAccess;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ReferencePhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('departmentRole:coordinator');
    }

    public function index(Request $request)
    {
        $departmentIds = LmsAccess::departmentIds('coordinator');
        $query = StudentRecord::with(['user', 'my_class.class_type', 'section'])
            ->where('grad', 0)
            ->whereHas('user', function ($q) { $q->where('user_type', 'student'); })
            ->whereHas('my_class', function ($q) use ($departmentIds) { $q->whereIn('class_type_id', $departmentIds); });

        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')->orWhere('username', 'like', '%'.$search.'%');
            });
        }

        $records = $query->orderBy('my_class_id')->orderBy('section_id')->orderBy('id')
            ->paginate(25)->appends($request->query());

        return view('pages.academic.reference-photos.index', compact('records'));
    }

    public function update(Request $request, $userId)
    {
        $data = $request->validate([
            'reference_photo' => 'required|image|mimes:jpeg,jpg,png|max:5120|dimensions:max_width=4096,max_height=4096',
        ]);

        $user = User::where('user_type', 'student')->findOrFail($userId);
        $this->studentRecord($user);
        $newPath = $data['reference_photo']->store('student_references', 'public');
        if (!$newPath) {
            throw ValidationException::withMessages(['reference_photo' => 'The official reference photo could not be saved.']);
        }

        $oldPath = $user->reference_photo_path;
        try {
            DB::transaction(function () use ($user, $newPath) {
                $lockedUser = User::whereKey($user->id)->lockForUpdate()->firstOrFail();
                // Recheck placement while holding the student row to prevent stale/cross-department updates.
                $this->studentRecord($lockedUser);
                $lockedUser->reference_photo_path = $newPath;
                $lockedUser->save();
            });
        } catch (\Throwable $error) {
            Storage::disk('public')->delete($newPath);
            throw $error;
        }

        if ($oldPath && preg_match('#^student_references/[a-zA-Z0-9_-]+\.(jpg|jpeg|png)$#i', $oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()->route('academic.reference-photos.index', $request->only('search'))
            ->with('flash_success', 'Official reference photo updated for '.$user->name.'.');
    }

    public function show($userId, FaceVerificationService $faces)
    {
        $user = User::where('user_type', 'student')->findOrFail($userId);
        $this->studentRecord($user);
        try {
            $path = $faces->referencePath($user);
        } catch (\App\Exceptions\FaceVerificationException $error) {
            abort(404);
        }

        return response()->file($path, ['Cache-Control' => 'private, no-store']);
    }

    private function studentRecord(User $user)
    {
        $departmentIds = LmsAccess::departmentIds('coordinator');
        return StudentRecord::where('user_id', $user->id)->where('grad', 0)
            ->whereHas('my_class', function ($q) use ($departmentIds) { $q->whereIn('class_type_id', $departmentIds); })
            ->latest('id')->firstOrFail();
    }
}
