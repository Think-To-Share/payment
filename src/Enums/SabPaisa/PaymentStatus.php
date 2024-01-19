<?php
namespace ThinkToShare\Payment\Enums\SabPaisa;

enum PaymentStatus: string
{
    case SUCCESS = "SUCCESS";
    case FAILED = "FAILED";
    case INITIATED = "INITIATED";
    case ABORTED = "ABORTED";
}