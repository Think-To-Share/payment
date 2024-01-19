<?php
namespace ThinkToShare\Payment\Enums\CcAvenue;

enum PaymentStatus: string
{
    case SUCCESS = "Success";
    case FAILURE = "Failure";
    case INVALID = "Invalid";
    case ABORTED = "Aborted";
    case TIMEOUT = "Timeout";
}