<?php

namespace ThinkToShare\Payment\Gateways\CcAvenue;

use ThinkToShare\Payment\DataObjects\CcAvenue\PaymentData;
use ThinkToShare\Payment\Exceptions\NotSupportedMethodException;
use ThinkToShare\Payment\Models\Payment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ThinkToShare\Payment\Concerns\HasPaymentUrl;
use ThinkToShare\Payment\Contracts\Gateway;
use ThinkToShare\Payment\Customer;
use ThinkToShare\Payment\Enums\Gateway as GatewayEnum;
use ThinkToShare\Payment\Factory\PaymentFactory;
use ThinkToShare\Payment\Models\CcavenuePayment;
use ThinkToShare\Payment\Utils\QueryString;

class CcAvenueGateway implements Gateway
{
    use HasPaymentUrl;

    protected const SANDBOX_URL = 'https://test.ccavenue.com/gTransaction.do?command=initiateTransaction';

    protected const LIVE_URL = 'https://secure.ccavenue.com/gTransaction.do?command=initiateTransaction';

    protected const SANDBOX_ENQUIRY_URL = 'https://apitest.ccavenue.com/apis/servlet/DoWebTrans';

    protected const LIVE_ENQUIRY_URL = 'https://api.ccavenue.com/apis/servlet/DoWebTrans';

    protected PaymentFactory $paymentFactory;

    protected Crypto $crypto;

    public function __construct(
        protected array $config,
        ?Crypto $crypto = null,
        ?PaymentFactory $paymentFactory = null,
    ){
        $this->crypto = $crypto ?? app(Crypto::class);
        $this->paymentFactory = $paymentFactory ?? app(PaymentFactory::class);
    }

    public function create(float|int|string $amount, string $order_id , Customer $customer): Payment
    {
        $payment = $this->paymentFactory->create($amount, $order_id, $customer, GatewayEnum::CCAVENUE);

        $data = $this->collectPaymentData($payment,$customer);

        CcavenuePayment::create([
            'payment_id' => $payment->id,
            'enc_data' => $this->prepareEncryptedData($data),
        ]);

        return $payment;
    }

    public function redirectView(Payment $payment): View
    {
        return view('payment::ccavenue.redirect')->with([
            'encrypted_data' => $payment->ccavenuePayment->enc_data,
            'access_code' => $this->config['access_code']
        ]);
    }

    public function verifyRequest(Request $request): Payment
    {
       $payment_data = $this->prepareDecryptedData($request->encResp);

       $payment = $this->paymentFactory->fromOrderId($payment_data->order_id);

       $payment->gatewayModel->update([
            'amount' => $payment_data->amount,
            'trans_date' => $payment_data->trans_date,
            'status' => $payment_data->order_status,
            'tracking_id' =>$payment_data->tracking_id,
            'trans_fee' => $payment_data->trans_fee,
            'service_tax' => $payment_data->service_tax,
            'data' => $payment_data,
       ]);

       return $payment->fresh();
    }

    public function enquirePayment(Payment $payment)
    {
        $data = [
            'reference_no' => $payment->ccavenuePayment->tracking_id,
            'order_no' => $payment->order_id,
        ];

        $encryptedData = $this->crypto->encrypt(json_encode($data), $this->config['working_key']);

        $response = Http::post(self::getEnquireUrl(),[
            'access_code' => $this->config['access_code'],
            'enc_request' => $encryptedData,
            'command' => "orderStatusTracker",
            'request_type' => "JSON"
        ]);

        dd($response->body());  //TODO: Implementation

    }

    public function webhook(Request $request)
    {
        throw new NotSupportedMethodException('Webhook', 'CcAvenue');
    }

    protected function collectPaymentData(Payment $payment, Customer $customer): array
    {
        return [
            'merchant_id' => $this->config['merchant_id'],
            'currency' => $this->config['currency'],
            'redirect_url' => url($this->config['redirect_url']),
            'cancel_url' => url($this->config['cancel_url']),
            'language' =>$this->config['language'],
            'order_id' => $payment->order_id,
            'amount' => $payment->order_amount,

            ...$customer->getCcAvenueAttributes(),
        ];
    }

    protected function prepareEncryptedData(array $data): string
    {
        return $this->crypto->encrypt(
            plainText: QueryString::arrayToQuery($data),
            key: $this->config['working_key'],
        );
    }

    public function prepareDecryptedData(string $data): PaymentData
    {
        $decText = $this->crypto->decrypt(
            encryptedText: $data,
            key: $this->config['working_key'],
        );

        return PaymentData::from(QueryString::queryToArray($decText));
    }

    public function getCustomer(Payment $payment): Customer
    {
        /** @var WHdata $data */
        $data = $payment->gatewayModel->data;

        return Customer::create()
            ->setName($data->billing_name)
            ->setEmail($data->billing_email)
            ->setMobile($data->billing_tel)
            ->setAddress($data->billing_address)
            ->setModel($payment->resource);
    }
}