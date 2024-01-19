<?php
namespace ThinkToShare\Payment\Enums;

use App\Http\Requests\PaymentGatewayRequest;

enum Gateway: string
{
    case CCAVENUE = "ccavenue";
    case SABPAISA = "sabpaisa";
    case CASHFREE = "cashfree";

    public function getRelationshipName(): string
    {
        return match ($this) {
            Gateway::CASHFREE => 'cashfreePayment',
            Gateway::CCAVENUE => 'ccavenuePayment',
            Gateway::SABPAISA => 'subpaisaPayment',
        };
    }
}

