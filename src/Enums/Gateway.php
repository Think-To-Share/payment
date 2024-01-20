<?php
namespace ThinkToShare\Payment\Enums;

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
            Gateway::SABPAISA => 'sbpaisaPayment',
        };
    }
}

