<?php

declare(strict_types=1);

namespace ThinkToShare\Payment\Enums;

enum PaymentStatus: string
{
    case SUCCESS = 'success';
    case PENDING = 'pending';
    case FAILED = 'failed';
    case ABORTED = 'aborted';
    case EXTRA = 'extra';
}
