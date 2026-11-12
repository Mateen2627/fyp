<?php

namespace App\Services;

use App\Models\TimeTable;
use App\User;
use Illuminate\Http\UploadedFile;

/**
 * Integration point for the future AI model. It must verify identity and liveness
 * on the server. Never accept browser confidence scores or a verified flag.
 */
class FaceVerificationService
{
    public function verify(User $student, UploadedFile $capture, TimeTable $lesson)
    {
        return ['verified' => false, 'status' => 'pending', 'message' => 'Capture received. Attendance is pending because face verification is not configured yet.'];
    }
}
