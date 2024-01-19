<?php

namespace ThinkToShare\Payment\Gateways\SabPaisa;

use ThinkToShare\Payment\Exceptions\NotSupportedMethodException;
use ThinkToShare\Payment\Models\Payment;
use ThinkToShare\Payment\DataObjects\SabPaisa\PaymentData;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use ThinkToShare\Payment\Concerns\HasPaymentUrl;
use ThinkToShare\Payment\Contracts\Gateway;
use ThinkToShare\Payment\Customer;
use ThinkToShare\Payment\Enums\Gateway as GatewayEnum;
use ThinkToShare\Payment\Factory\PaymentFactory;
use ThinkToShare\Payment\Models\SubpaisaPayment;
use ThinkToShare\Payment\Utils\QueryString;

class SabPaisaGateway implements Gateway
{
    use HasPaymentUrl;

    protected const SANDBOX_URL = 'https://stage-securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1';

    protected const LIVE_URL = 'https://securepay.sabpaisa.in/SabPaisa/sabPaisaInit?v=1';

    protected const SANDBOX_ENQUIRY_URL = 'https://stage-txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId';

    protected const LIVE_ENQUIRY_URL = 'https://txnenquiry.sabpaisa.in/SPTxtnEnquiry/getTxnStatusByClientxnId';

    protected PaymentFactory $paymentFactory;

    protected Crypto $crypto;

    public function __construct(
        protected array $config,
        ?Crypto $crypto = null,
        ?PaymentFactory $paymentFactory = null
    ){
        $this->crypto = $crypto ?? app(Crypto::class);
        $this->paymentFactory = $paymentFactory ?? app(PaymentFactory::class);
    }

    public function create(float|int|string $amount, string $order_id, Customer $customer): Payment
    {
        $payment = $this->paymentFactory->create($amount, $order_id, $customer, GatewayEnum::SABPAISA);

        $data = $this->collectPaymentData($payment, $customer);

        SubpaisaPayment::create([
            'payment_id' => $payment->id,
            'enc_data' => $this->prepareEncryptedData($data),
        ]);

       return $payment;
    }

    public function redirectView(Payment $payment): View
    {
        return view('payment::subpaisa.redirect')->with([
            'data' => $payment->subpaisaPayment->enc_data,
            'clientCode' => $this->config['client_code']
        ]);
    }

    public function verifyRequest(Request $request): Payment
    {
        $payment_data = $this->prepareDecryptedData($request->encResponse);

        $payment = $this->paymentFactory->fromOrderId($payment_data->clientTxnId);

        $payment->subpaisaPayment->update([
             'trans_date' => $payment_data->transDate,
             'status' => $payment_data->status,
             'sabpaisaTxnId' =>$payment_data->sabpaisaTxnId,
             'amount' => $payment_data->paidAmount,
             'data' => $payment_data,
        ]);

        return $payment->fresh();
    }

    protected function collectPaymentData(Payment $payment, Customer $customer): array
    {
        return [
            'clientCode' => $this->config['client_code'],
            'transUserName' => $this->config['username'],
            'transUserPassword' => $this->config['password'],
            'clientTxnId' => $payment->order_id,
            'amount' => $payment->order_amount,
            'amountType' => 'INR',
            'mcc' => 5137,
            'channelId' => 'W',
            'callbackUrl' => url($this->config['callbackUrl']),

            ...$customer->getSubPaisaAttributes(),
        ];
    }

    protected function prepareEncryptedData(array $data): string
    {
        return $this->crypto->encrypt(
            key: $this->config['auth_key'],
            iv: $this->config['auth_iv'],
            plaintext: QueryString::arrayToQuery($data),
        );
    }

    public function prepareDecryptedData(string $data, ?string $iv = null): PaymentData
    {
        $decText = $this->crypto->decrypt(
            key: $this->config['auth_key'],
            data: $data,
            iv: $iv,
        );

        return PaymentData::from(QueryString::queryToArray($decText));
    }


    public function enquirePayment(Payment $payment): void
    {
        $data = [
            'clientCode' => $this->config['client_code'],
            'clientTxnId' => $payment->order_id,
        ];

        $encryptedData = $this->prepareEncryptedData($data);

        $response = Http::post($this->getEnquireUrl(),[
            'clientCode' => $this->config['client_code'],
            'statusTransEncData' => $encryptedData,
        ]);

        $responseData = $response->json();

        $payment_data = $this->prepareDecryptedData($responseData['statusResponseData'], $this->config['auth_iv']);

        $payment->gatewayModel->update([
            'trans_date' => $payment_data->transDate,
            'status' => $payment_data->status,
            'enquiry_data' => $payment_data,
        ]);
    }

    public function webhook(Request $request)
    {
        throw new NotSupportedMethodException('Webhook', 'SabPaisa');
    }

    public function getCustomer(Payment $payment): Customer
    {
        /** @var WHdata $data */
        $data = $payment->gatewayModel->data;

        return Customer::create()
            ->setName($data->payerName)
            ->setEmail($data->payerEmail)
            ->setMobile($data->payerMobile)
            ->setAddress($data->payerAddress)
            ->setModel($payment->resource);
    }
}
