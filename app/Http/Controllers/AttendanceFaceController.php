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
            'latitude' => $request->input('latitude'), 'longitude' => $request->input('longitude'),
            'location_accuracy' => $request->input('location_accuracy'),
        ], [
            'time_table_id' => 'required|integer|exists:time_tables,id',
            'live_image' => 'required|image|mimes:jpeg,jpg,png|max:5120|dimensions:max_width=4096,max_height=4096',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location_accuracy' => 'required|numeric|gt:0|lte:1000',
        ]);
        if ($validation->fails()) {
            $locationFields = ['latitude', 'longitude', 'location_accuracy'];
            $code = $validation->errors()->hasAny($locationFields) ? 'invalid_location' : 'invalid_capture';
            return response()->json(['verified' => false, 'code' => $code, 'message' => $validation->errors()->first(), 'errors' => $validation->errors()], 422);
        }
        // DeepFace may load its weights on the first request. Keep PHP alive long enough
        // for the configured FastAPI timeout plus a short response/cleanup margin.
        @set_time_limit(max(330, (int) config('services.face_verification.timeout', 300) + 30));
        try {
            $result = $attendance->mark($request->user(), $request->input('time_table_id'), $capture, $verifier, [
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'accuracy' => $request->input('location_accuracy'),
            ]);
        } catch (FaceVerificationException $error) {
            return response()->json(array_merge(['verified' => false, 'code' => $error->reason, 'message' => $error->getMessage()], $error->details), $error->status);
        }
        return response()->json([
            'verified' => true, 'status' => 'verified', 'message' => 'Face verified. Your attendance has been marked present.',
            'attendance_id' => $result['attendance']->id, 'distance' => $result['distance'], 'threshold' => $result['threshold'],
            'match_confidence' => $result['match_confidence'] ?? null, 'face_box' => $result['face_box'] ?? null,
            'spoof_check' => $result['spoof_check'] ?? 'passed',
        ]);
    }
}
