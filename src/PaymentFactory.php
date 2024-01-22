<?php

namespace ThinkToShare\Payment;

use ThinkToShare\Payment\Enums\PaymentStatus;
use ThinkToShare\Payment\Models\Payment;
use ThinkToShare\Payment\Enums\Gateway;

class PaymentFactory
{
    public function create(float|int|string $amount, string $order_id, Customer $customer, Gateway $gateway): Payment
    {
        return Payment::create([
            'order_id' => $order_id,
            'order_amount' => $amount,
            'gateway' => $gateway,
            'status' => PaymentStatus::PENDING,
            'resource_id' => $customer->model->getKey(),
            'resource_type' => $customer->model->getMorphClass(),
        ]);
    }

    public function fromOrderId(string $order_id): Payment
    {
        $payment = Payment::where('order_id', $order_id)->first();

        if(is_null($payment)) {
            throw new \Exception("Unable to find payment with OrderId [{$order_id}]");
        }

        return $payment;
    }

    public function syncGatewayStatus(Payment $payment): Payment
    {
        $gatewayModel = $payment->gatewayModel->refresh();

        return $this->updateStatus($payment, $gatewayModel->status->getGenericStatus());
    }

    public function updateStatus(Payment $payment, PaymentStatus $status): Payment
    {
        $payment->update([
            'status' => $status,
        ]);

        return $payment->refresh();
    }
}
