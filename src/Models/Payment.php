<?php

namespace ThinkToShare\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use ThinkToShare\Payment\Enums\Gateway;
use Illuminate\Database\Eloquent\Casts\Attribute;
use ThinkToShare\Payment\Enums\PaymentStatus;
use ThinkToShare\Payment\Facades\Payment as PaymentFacade;

class Payment extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $casts = [
        'order_amount' => 'float',
        'status' => PaymentStatus::class,
        'gateway' => Gateway::class,
    ];

    public function resource(): MorphTo
    {
        return $this->morphTo();
    }

    public function sabpaisaPayment(): HasOne
    {
        return $this->hasOne(SabpaisaPayment::class);
    }

    public function ccavenuePayment(): HasOne
    {
        return $this->hasOne(CcavenuePayment::class);
    }

    public function cashfreePayment(): HasOne
    {
        return $this->hasOne(CashfreePayment::class);
    }

    public function enquire(): static
    {
        return PaymentFacade::gateway($this->gateway)->enquirePayment($this);
    }

    protected function gatewayModel(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $relationship = $this->gateway->getRelationshipName();
                return $this->{$relationship};
            },
        );
    }
}
