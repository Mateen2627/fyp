<?php

namespace App\Exceptions;

use RuntimeException;

class FaceVerificationException extends RuntimeException
{
    public $status;
    public $reason;

    public function __construct($message, $status = 422, $reason = 'face_not_verified')
    {
        parent::__construct($message);
        $this->status = $status;
        $this->reason = $reason;
    }
}
