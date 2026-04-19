<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentFaceProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $records = StudentAttendance::where('user_id', Auth::id())->latest('attendance_date')->paginate(30);
        $faceProfile = StudentFaceProfile::where('user_id', Auth::id())->first();

        return view('pages.student.modules.attendance.index', compact('records', 'faceProfile'));
    }

    public function registerFace(Request $request)
    {
        $data = $request->validate([
            'face_token' => 'required|string|min:4|max:255',
            'face_image' => 'nullable|image|max:5120',
        ]);

        $payload = [
            'face_token' => $data['face_token'],
            'is_active' => 1,
        ];

        if ($request->hasFile('face_image')) {
            $payload['face_image_path'] = $request->file('face_image')->store('face_profiles', 'public');
        }

        StudentFaceProfile::updateOrCreate(['user_id' => Auth::id()], $payload);

        return redirect()->route('student.attendance.index')->with('flash_success', 'Face profile registered successfully.');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'mode' => 'required|in:manual,facial',
            'remarks' => 'nullable|string',
            'face_token' => 'nullable|string',
        ]);

        $facialVerified = false;

        if ($data['mode'] === 'facial') {
            $profile = StudentFaceProfile::where('user_id', Auth::id())->where('is_active', 1)->first();
            if (!$profile) {
                return redirect()->route('student.attendance.index')->with('flash_danger', 'Please register your face before using facial attendance.');
            }

            if (($data['face_token'] ?? null) !== $profile->face_token) {
                return redirect()->route('student.attendance.index')->with('flash_danger', 'Facial authentication failed (dummy check).');
            }

            $facialVerified = true;
        }

        StudentAttendance::updateOrCreate(
            ['user_id' => Auth::id(), 'attendance_date' => $data['attendance_date']],
            [
                'status' => $data['status'],
                'mode' => $data['mode'],
                'facial_verified' => $facialVerified,
                'remarks' => $data['remarks'] ?? null,
            ]
        );

        return redirect()->route('student.attendance.index')->with('flash_success', 'Attendance marked successfully.');
    }
}
