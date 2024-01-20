<?php

declare(strict_types=1);

namespace ThinkToShare\Payment\Enums;

enum PaymentStatus
{
    case SUCCESS;
    case PENDING;
    case FAILED;
    case EXTRA;
}
