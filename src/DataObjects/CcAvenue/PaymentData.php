<?php

namespace ThinkToShare\Payment\DataObjects\CcAvenue;

use ThinkToShare\Payment\Enums\CcAvenue\PaymentStatus;
use ThinkToShare\Payment\Enums\CcAvenue\VaultStatus;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class PaymentData extends Data
{
    public function __construct(
        public readonly string $order_id,
        public readonly int $tracking_id,
        public readonly ?string $bank_ref_no,
        public readonly PaymentStatus $order_status,
        public readonly ?string $failure_message,
        public readonly ?string $payment_mode,
        public readonly ?string $card_name,
        public readonly ?int $status_code,
        public readonly ?string $status_message,
        public readonly string $currency,
        public readonly float $amount,
        public readonly ?string $billing_name,
        public readonly ?string $billing_address,
        public readonly ?string $billing_city,
        public readonly ?string $billing_state,
        public readonly ?string $billing_zip,
        public readonly ?string $billing_country,
        public readonly ?int $billing_tel,
        public readonly ?string $billing_email,
        public readonly ?string $delivery_name,
        public readonly ?string $delivery_address,
        public readonly ?string $delivery_city,
        public readonly ?string $delivery_state,
        public readonly ?string $delivery_zip,
        public readonly ?string $delivery_country,
        public readonly ?int $delivery_tel,
        public readonly ?string $merchant_param1,
        public readonly ?string $merchant_param2,
        public readonly ?string $merchant_param3,
        public readonly ?string $merchant_param4,
        public readonly ?string $merchant_param5,
        public readonly VaultStatus $vault,   //Y or N
        public readonly ?string $offer_type,
        public readonly ?string $offer_code,
        public readonly float $discount_value,
        public readonly float $mer_amount,
        public readonly ?int $eci_value,
        public readonly ?string $retry,
        public readonly ?string $response_code,
        public readonly ?string $billing_notes,
        #[WithCast(DateTimeInterfaceCast::class, type: CarbonImmutable::class, format: 'd/m/Y H:i:s', timeZone: 'Asia/Kolkata')]
        public readonly ?CarbonImmutable $trans_date,
        public readonly ?string $bin_country,
        public readonly float $trans_fee,
        public readonly float $service_tax,
        public readonly ?string $merchant_id,
        public readonly ?string $order_url,
        public readonly ?string $version_3DS,

    ) {
    }
}


?>
