<?php

namespace ThinkToShare\Payment\Enums\CcAvenue;

use ThinkToShare\Payment\Enums\PaymentStatus as GenericStatus;

enum PaymentStatus: string
{
    case SUCCESS = "Success";
    case FAILURE = "Failure";
    case INVALID = "Invalid";
    case ABORTED = "Aborted";
    case TIMEOUT = "Timeout";

    public function getGenericStatus(): GenericStatus
    {
        return match ($this) {
            self::SUCCESS => GenericStatus::SUCCESS,
            self::FAILURE => GenericStatus::FAILED,
            self::INVALID, self::ABORTED, self::TIMEOUT => GenericStatus::EXTRA,
        };
    }
}
