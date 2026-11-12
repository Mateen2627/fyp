<?php

namespace App\Exceptions;

use RuntimeException;

class FaceVerificationException extends RuntimeException
{
    public $status;
    public $reason;
    public $details;

    public function __construct($message, $status = 422, $reason = 'face_not_verified', array $details = [])
    {
        parent::__construct($message);
        $this->status = $status;
        $this->reason = $reason;
        $this->details = $details;
    }
}
