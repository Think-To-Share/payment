<?php

namespace ThinkToShare\Payment\Facades;

use Illuminate\Support\Facades\Facade;
use ThinkToShare\Payment\Contracts\Gateway;

/**
 * @method static \ThinkToShare\Payment\Models\Payment create(int|string|float $amount, string $order_id, \ThinkToShare\Payment\Customer $customer)
 * @method static \ThinkToShare\Payment\Contracts\Gateway gateway(string|null $name = null)
 * @method static \Illuminate\Contracts\View\View redirectView(\ThinkToShare\Payment\Models\Payment $payment)
 * @method static \ThinkToShare\Payment\Customer getCustomer(\ThinkToShare\Payment\Models\Payment $payment)
 *
 * @see \ThinkToShare\Payment\Contracts\Gateway
 */
class Payment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Gateway::class;
    }
}
