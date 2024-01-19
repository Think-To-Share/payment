<?php

namespace ThinkToShare\Payment\Models;

use Cashfree\Model\WHdata;
use Cashfree\ObjectSerializer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CashfreePayment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'payment_time' => 'datetime',
        'order_amount' => 'float',
        'payment_amount' => 'float',
        'data' => 'array'
    ];

    protected function data(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $value = json_decode($value, true);
                return ObjectSerializer::deserialize($value, WHdata::class);
            },
            set: function ($value) {
                return [
                    'data' => json_encode($value),
                ];   
            }
        );
    }
}
