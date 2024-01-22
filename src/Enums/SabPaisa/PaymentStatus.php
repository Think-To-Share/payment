<?php
namespace ThinkToShare\Payment\Enums\SabPaisa;

use ThinkToShare\Payment\Enums\PaymentStatus as GenericStatus;

enum PaymentStatus: string
{
    case SUCCESS = "SUCCESS";
    case FAILED = "FAILED";
    case INITIATED = "INITIATED";
    case ABORTED = "ABORTED";

    public function getGenericStatus(): GenericStatus
    {
        return match ($this) {
            self::SUCCESS => GenericStatus::SUCCESS,
            self::FAILED => GenericStatus::FAILED,
            self::ABORTED => GenericStatus::ABORTED,
            self::INITIATED => GenericStatus::PENDING,
        };
    }
}
