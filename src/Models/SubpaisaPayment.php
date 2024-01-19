<?php

namespace ThinkToShare\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ThinkToShare\Payment\DataObjects\SabPaisa\PaymentData;

class SubpaisaPayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'trans_date' => 'datetime',
        'amount' => 'float',
        'data' => PaymentData::class,
        'enquiry_data' => PaymentData::class,
    ];

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
}
