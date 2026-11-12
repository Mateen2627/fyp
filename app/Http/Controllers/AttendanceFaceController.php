<?php

namespace App\Http\Controllers;

use App\Exceptions\FaceVerificationException;
use App\Services\FaceAttendanceService;
use App\Services\FaceVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceFaceController extends Controller
{
    public function __construct() { $this->middleware(['auth', 'student']); }

    public function markAttendance(Request $request, FaceAttendanceService $attendance, FaceVerificationService $verifier)
    {
        // Preserve the old check-in URL/field without creating another write workflow.
        $capture = $request->file('live_image') ?: $request->file('capture');
        $validation = Validator::make([
            'time_table_id' => $request->input('time_table_id'), 'live_image' => $capture,
        ], [
            'time_table_id' => 'required|integer|exists:time_tables,id',
            'live_image' => 'required|image|mimes:jpeg,jpg,png|max:5120|dimensions:max_width=4096,max_height=4096',
        ]);
        if ($validation->fails()) {
            return response()->json(['verified' => false, 'code' => 'invalid_capture', 'message' => $validation->errors()->first(), 'errors' => $validation->errors()], 422);
        }
        try {
            $result = $attendance->mark($request->user(), $request->input('time_table_id'), $capture, $verifier);
        } catch (FaceVerificationException $error) {
            return response()->json(['verified' => false, 'code' => $error->reason, 'message' => $error->getMessage()], $error->status);
        }
        return response()->json([
            'verified' => true, 'status' => 'verified', 'message' => 'Face verified. Your attendance has been marked present.',
            'attendance_id' => $result['attendance']->id, 'distance' => $result['distance'], 'threshold' => $result['threshold'],
        ]);
    }
}
