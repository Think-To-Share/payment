<?php

namespace ThinkToShare\Payment;

use Illuminate\Contracts\Foundation\Application;
use InvalidArgumentException;
use ThinkToShare\Payment\Contracts\Gateway;
use ThinkToShare\Payment\Enums\Gateway as GatewayEnum;
use ThinkToShare\Payment\Gateways\Cashfree\CashfreeGateway;
use ThinkToShare\Payment\Gateways\CcAvenue\CcAvenueGateway;
use ThinkToShare\Payment\Gateways\CcAvenue\Crypto as CcAvenueCrypto;
use ThinkToShare\Payment\Gateways\SabPaisa\Crypto as SabPaisaCrypto;
use ThinkToShare\Payment\Gateways\SabPaisa\SabPaisaGateway;

class PaymentManager
{
    protected array $gateways = [];

    public function __construct(protected Application $app)
    {
    }

    public function gateway(string|null|GatewayEnum $name = null): Gateway
    {
        if($name instanceof GatewayEnum){
            $name = $name->value;
        }
        
        $name = $name ?: $this->getDefaultGateway();
        return $this->gateways[$name] = $this->get($name);
    }

    public function getDefaultGateway(): string
    {
        return $this->app['config']['payment.default'];
    }

    protected function get(string $name)
    {
        return $this->gateways[$name] ?? $this->resolve($name);
    }

    public function resolve(string $name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new InvalidArgumentException("Gateway [{$name}] is not defined.");
        }

        $gatewayMethod = 'create'.ucfirst($name).'Gateway';

        if (! method_exists($this, $gatewayMethod)) {
            throw new InvalidArgumentException("Gateway [{$name}] is not supported.");
        }

        return $this->{$gatewayMethod}($config);

    }

    protected function createSabPaisaGateway(array $config): SabPaisaGateway
    {
        return new SabPaisaGateway($config, app(SabPaisaCrypto::class));
    }

    protected function createCashfreeGateway(array $config): CashfreeGateway
    {
        return new CashfreeGateway($config);
    }

    protected function createCcAvenueGateway(array $config): CcAvenueGateway
    {
        return new CcAvenueGateway($config, app(CcAvenueCrypto::class));
    }

    protected function getConfig(string $name): ?array
    {
        return $this->app['config']["payment.gateways.{$name}"];
    }

    public function __call(string $method, array $arguments)
    {
        return $this->gateway()->$method(...$arguments);
    }
}
