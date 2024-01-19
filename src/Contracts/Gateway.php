<?php

namespace ThinkToShare\Payment\Contracts;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use ThinkToShare\Payment\Customer;
use ThinkToShare\Payment\Models\Payment;

interface Gateway
{
    public function create(int|string|float $amount, string $order_id, Customer $customer): Payment;

    public function verifyRequest(Request $request): Payment;

    public function redirectView(Payment $payment): View;

    public function enquirePayment(Payment $payment);

    public function webhook(Request $request);

    public function getCustomer(Payment $payment): Customer;
}
