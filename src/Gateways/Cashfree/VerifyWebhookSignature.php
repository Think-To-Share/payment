<?php

namespace ThinkToShare\Payment\Gateways\Cashfree;

use Illuminate\Http\Request;
use ThinkToShare\Payment\Exceptions\VerifySignatureException;

class VerifyWebhookSignature
{
    public function verify(Request $request, string $key): Request
    {
        $signature = $request->header("x-webhook-signature");
        $timespan = $request->header("x-webhook-timestamp");

        $postData = $timespan . $request->getContent();

        $hmac  = hash_hmac('sha256', $postData, $key, true);
        $gen_signature  = base64_encode($hmac);

        if ($signature !== $gen_signature) {
            throw new VerifySignatureException($signature);
        }

        return $request;
    }
}
