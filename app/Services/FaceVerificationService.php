<?php

namespace App\Services;

use App\Exceptions\FaceVerificationException;
use App\Models\TimeTable;
use App\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FaceVerificationService
{
    public function referencePath(User $student)
    {
        $path = $student->reference_photo_path;
        if (!is_string($path) || !preg_match('#^student_references/[a-zA-Z0-9_-]+\.(jpg|jpeg|png)$#i', $path) ||
            !Storage::disk('public')->exists($path)) {
            throw new FaceVerificationException('Ask your department coordinator or an administrator to upload your official reference photo before marking attendance.', 422, 'reference_photo_missing');
        }
        return Storage::disk('public')->path($path);
    }

    public function verify(User $student, UploadedFile $capture, TimeTable $lesson)
    {
        $reference = $this->referencePath($student);
        $referenceStream = @fopen($reference, 'rb');
        $liveStream = @fopen($capture->getRealPath(), 'rb');
        if (!is_resource($referenceStream) || !is_resource($liveStream)) {
            if (is_resource($referenceStream)) { fclose($referenceStream); }
            if (is_resource($liveStream)) { fclose($liveStream); }
            throw new FaceVerificationException('The reference photo or camera capture could not be read. Please try again.', 422, 'image_unavailable');
        }
        try {
            $response = Http::acceptJson()->asMultipart()
                ->withOptions([
                    'connect_timeout' => max(1, (int) config('services.face_verification.connect_timeout', 5)),
                    'allow_redirects' => false,
                ])
                ->timeout(max(1, (int) config('services.face_verification.timeout', 60)))
                ->attach('reference_image', $referenceStream, basename($reference), ['Content-Type' => mime_content_type($reference)])
                ->attach('live_image', $liveStream, 'live.'.$capture->extension(), ['Content-Type' => $capture->getMimeType()])
                ->post(config('services.face_verification.url'));
        } catch (ConnectionException $error) {
            throw new FaceVerificationException('Face verification is unavailable. Please try again after the recognition service is running.', 503, 'service_unavailable');
        } finally {
            if (is_resource($referenceStream)) { fclose($referenceStream); }
            if (is_resource($liveStream)) { fclose($liveStream); }
        }

        if (!$response->successful()) {
            throw new FaceVerificationException('The recognition service could not complete verification. Attendance was not marked; please try again.', 502, 'service_error');
        }
        $result = $response->json();
        if (!is_array($result) || !array_key_exists('verified', $result) || !is_bool($result['verified'])) {
            throw new FaceVerificationException('The recognition service returned an invalid response. Attendance was not marked.', 502, 'invalid_service_response');
        }
        $details = $this->displayDetails($result);
        if ($result['verified'] !== true) {
            $error = strtolower(is_string($result['error'] ?? null) ? $result['error'] : '');
            if (strpos($error, 'spoof') !== false || strpos($error, 'fake') !== false) {
                throw new FaceVerificationException('A possible spoof was detected. Use your live face, not a photo or screen, and try again.', 422, 'spoof_detected');
            }
            if (strpos($error, 'face') !== false && (strpos($error, 'detect') !== false || strpos($error, 'found') !== false)) {
                throw new FaceVerificationException('A clear face could not be detected. Face the camera in good lighting and try again.', 422, 'face_not_detected');
            }
            throw new FaceVerificationException('Your face did not match your official reference photo. Attendance was not marked.', 422, 'face_mismatch', $details);
        }
        $distance = $result['distance'] ?? null;
        $threshold = $result['threshold'] ?? null;
        $numeric = function ($value) { return (is_int($value) || is_float($value)) && is_finite((float) $value); };
        if (!empty($result['error']) || !$numeric($distance) || !$numeric($threshold) || $distance < 0 ||
            abs($threshold - (float) config('services.face_verification.threshold')) > 0.000001) {
            throw new FaceVerificationException('The recognition service returned inconsistent verification data. Attendance was not marked.', 502, 'invalid_service_response');
        }
        if ($distance > $threshold) {
            throw new FaceVerificationException('Your face did not match your official reference photo. Attendance was not marked.', 422, 'face_mismatch');
        }
        return array_merge(['verified' => true, 'distance' => (float) $distance, 'threshold' => (float) $threshold], $details, ['spoof_check' => 'passed']);
    }

    private function displayDetails(array $result)
    {
        $details = [];
        $distance = $result['distance'] ?? null;
        $threshold = $result['threshold'] ?? null;
        $numeric = function ($value) { return (is_int($value) || is_float($value)) && is_finite((float) $value); };
        if ($numeric($distance) && $numeric($threshold) && $distance >= 0 &&
            abs($threshold - (float) config('services.face_verification.threshold')) <= 0.000001) {
            $details['distance'] = (float) $distance;
            $details['threshold'] = (float) $threshold;
        }
        $confidence = $result['match_confidence'] ?? null;
        if ($numeric($confidence) && $confidence >= 0 && $confidence <= 100) {
            $details['match_confidence'] = round((float) $confidence, 1);
        }
        $box = $result['face_box'] ?? null;
        if (is_array($box)) {
            $coordinates = [];
            foreach (['x', 'y', 'w', 'h'] as $key) {
                $value = $box[$key] ?? null;
                if (!$numeric($value)) { return $details; }
                $coordinates[$key] = (int) $value;
            }
            if ($coordinates['x'] >= 0 && $coordinates['y'] >= 0 && $coordinates['w'] > 0 && $coordinates['h'] > 0) {
                $details['face_box'] = $coordinates;
            }
        }
        if (($result['spoof_check'] ?? null) === 'passed') { $details['spoof_check'] = 'passed'; }
        return $details;
    }
}
