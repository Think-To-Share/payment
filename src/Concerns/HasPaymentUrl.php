<?php

namespace ThinkToShare\Payment\Concerns;

use Illuminate\Support\Facades\Config;

trait HasPaymentUrl
{
    public static function getPaymentUrl(): string
    {
        if(Config::get('payment.sandbox')){
            return self::SANDBOX_URL;
        }else{
            return self::LIVE_URL;
        }
    }

    public static function getEnquireUrl(): string
    {
        if(Config::get('payment.sandbox')){
            return self::SANDBOX_ENQUIRY_URL;
        }else{
            return self::LIVE_ENQUIRY_URL;
        }
    }
}
