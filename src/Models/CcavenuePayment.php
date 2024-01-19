<?php

namespace ThinkToShare\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ThinkToShare\Payment\DataObjects\CcAvenue\PaymentData;
use ThinkToShare\Payment\Enums\CcAvenue\PaymentStatus;

class CcavenuePayment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'trans_date' => 'datetime',
        'status' => PaymentStatus::class,
        'amount' => 'float',
        'data' => PaymentData::class,
    ];
}
