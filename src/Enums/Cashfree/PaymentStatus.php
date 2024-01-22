<?php

namespace ThinkToShare\Payment\Enums\Cashfree;

use ThinkToShare\Payment\Enums\PaymentStatus as GenericStatus;

enum PaymentStatus: string
{
    case SUCCESS = 'SUCCESS';
    case FAILED = 'FAILED';
    case NOT_ATTEMPTED = 'NOT_ATTEMPTED';
    case USER_DROPPED = 'USER_DROPPED';
    case VOID = 'VOID';
    case CANCELLED = 'CANCELLED';
    case PENDING = 'PENDING';

    public function getGenericStatus(): GenericStatus
    {
        return match ($this) {
            self::SUCCESS => GenericStatus::SUCCESS,
            self::FAILED => GenericStatus::FAILED,
            self::USER_DROPPED, self::NOT_ATTEMPTED, self::CANCELLED => GenericStatus::ABORTED,
            self::PENDING => GenericStatus::PENDING,
            self::VOID => GenericStatus::EXTRA,
        };
    }
}
