<?php

namespace ThinkToShare\Payment\DataObjects\SabPaisa;

use ThinkToShare\Payment\Enums\SabPaisa\PaymentStatus;
use Carbon\CarbonImmutable;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class PaymentData extends Data
{
    public function __construct(
        public readonly string $payerName,
        public readonly string $payerEmail,
        public readonly int $payerMobile,
        public readonly string $clientTxnId,
        public readonly ?string $payerAddress,
        public readonly int $amount,
        public readonly string $clientCode,
        public readonly string $paidAmount,
        public readonly string $paymentMode,
        public readonly string $bankName,
        public readonly ?string $amountType,
        public readonly PaymentStatus $status,
        public readonly int $statusCode,
        public readonly ?string $challanNumber,
        public readonly string $sabpaisaTxnId,
        public readonly string $sabpaisaMessage,
        public readonly ?string $bankMessage,
        public readonly ?string $bankErrorCode,
        public readonly ?string $sabPaisaErrorCode,
        public readonly ?string $bankTxnld,
        public readonly ?string $programld,
        public readonly ?int $mcc,
        #[WithCast(DateTimeInterfaceCast::class, type: CarbonImmutable::class, format: ['D M d H:i:s T Y', 'Y-m-d H:i:s.v'])]
        public readonly CarbonImmutable $transDate,
        public readonly ?string $udf1,
        public readonly ?string $udf2,
        public readonly ?string $udf3,
        public readonly ?string $udf4,
        public readonly ?string $udf5,
        public readonly ?string $udf6,
        public readonly ?string $udf7,
        public readonly ?string $udf8,
        public readonly ?string $udf9,
        public readonly ?string $udf11,
        public readonly ?string $udf12,
        public readonly ?string $udf13,
        public readonly ?string $udf14,
        public readonly ?string $udf15,
        public readonly ?string $udf16,
        public readonly ?string $udf17,
        public readonly ?string $udf18,
        public readonly ?string $udf19,
        public readonly ?string $udf20,

    ) {
    }
}
