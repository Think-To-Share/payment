<?php

namespace ThinkToShare\Payment\Gateways\Cashfree;

use Cashfree\Model\OrderMeta;
use ThinkToShare\Payment\Exceptions\NotSupportedMethodException;
use ThinkToShare\Payment\Models\Payment;
use Cashfree\Cashfree;
use Cashfree\Model\CreateOrderRequest;
use Cashfree\Model\CreateOrderRequestOrderMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use ThinkToShare\Payment\Contracts\Gateway;
use ThinkToShare\Payment\Customer;
use ThinkToShare\Payment\Factory\PaymentFactory;
use Cashfree\Model\WHdata;
use Cashfree\ObjectSerializer;
use ThinkToShare\Payment\Enums\Gateway as GatewayEnum;
use ThinkToShare\Payment\Models\CashfreePayment;

class CashfreeGateway implements Gateway
{
    protected PaymentFactory $paymentFactory;

    protected VerifyWebhookSignature $verifyWebhookSignature;

    protected Cashfree $cashfreeClient;

    const CASHFREE_VERSION = '2022-09-01';

    public function __construct(
        protected array $config,
        ?Cashfree $cashfreeClient = null,
        ?PaymentFactory $paymentFactory = null,
        ?VerifyWebhookSignature $verifyWebhookSignature = null,
        ){
            $this->cashfreeClient = $cashfreeClient ?? app(Cashfree::class);
            $this->paymentFactory = $paymentFactory ?? app(PaymentFactory::class);
            $this->verifyWebhookSignature = $verifyWebhookSignature ?? app(VerifyWebhookSignature::class);
        }

    public function create(float|int|string $amount,String $order_id, Customer $customer): Payment
    {
        $payment = $this->paymentFactory->create($amount, $order_id, $customer, GatewayEnum::CASHFREE);

        $order = $this->createCashfreeOrder($payment, $customer);

        CashfreePayment::create([
            'payment_id' => $payment->id,
            'enc_data' => $order[0]->getPaymentSessionId(),
        ]);

        return $payment;
    }

    public function verifyRequest(Request $request): Payment
    {
       throw new NotSupportedMethodException('Verify', 'Cashfree');
    }

    public function redirectView(Payment $payment): View
    {
        return view('payment::cashfree.redirect')->with([
            'session_id' => $payment->cashfreePayment->enc_data
        ]);
    }

    public function enquirePayment(Payment $payment)
    {
        throw new NotSupportedMethodException('EnquirePayment', 'Cashfree');
    }

    public function webhook(Request $request): Payment
    {
        $wh_data = $this->verifyWebhook($request);

        $payment = $this->paymentFactory->fromOrderId($wh_data->getOrder()->getOrderId());

        $payment->gatewayModel->update([
            'order_amount' => $wh_data->getOrder()->getOrderAmount(),
            'payment_status' => $wh_data->getPayment()->getPaymentStatus(),
            'payment_amount' => $wh_data->getPayment()->getPaymentAmount(),
            'payment_time' => $wh_data->getPayment()->getPaymentTime(),
            'payment_mode' => $wh_data->getPayment()->getPaymentGroup(),
            'customer_id' => $wh_data->getCustomerDetails()->getCustomerId(),
            'payment_phone' => $wh_data->getCustomerDetails()->getCustomerPhone(),
            'data' => $wh_data,
        ]);

        return $payment;
    }

    protected function createCashfreeOrder(Payment $payment, Customer $customer): array
    {
        /** @var OrderMeta $order_meta */
        $order_meta = (new CreateOrderRequestOrderMeta())
            ->setReturnUrl(url($this->config['return_url']));

        $order = (new CreateOrderRequest())
            ->setOrderAmount($payment->order_amount)
            ->setOrderCurrency($this->config['currency'])
            ->setOrderId($payment->order_id)
            ->setCustomerDetails($customer->getCashfreeCustomer())
            ->setOrderMeta($order_meta);

        return $this->cashfreeClient->PGCreateOrder(self::CASHFREE_VERSION, $order);
    }

    public function verifyWebhook(Request $request): WHdata
    {
        $wh_data = $this->verifyWebhookSignature->verify(
            request: $request,
            key: $this->config['client_secret_key']
        );

        /** @var WHdata $data */
        $data = ObjectSerializer::deserialize($wh_data['data'], WHdata::class);

        return $data;
    }

    public function getCustomer(Payment $payment): Customer
    {
        /** @var WHdata $data */
        $data = $payment->gatewayModel->data;

        return Customer::create()
            ->setName($data->getCustomerDetails()->getCustomerName())
            ->setEmail($data->getCustomerDetails()->getCustomerEmail())
            ->setMobile($data->getCustomerDetails()->getCustomerPhone())
            ->setModel($payment->resource);
    }
}
