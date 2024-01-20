<?php

namespace ThinkToShare\Payment;

use Cashfree\Model\CustomerDetails;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Model;

class Customer implements Arrayable, Jsonable
{
    public readonly string $name;
    public readonly string $email;
    public readonly string $mobile;
    public readonly string $address;
    public readonly Model $model;

    public static function create(): static
    {
        return new static();
    }

    public function setModel(Model $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function setMobile(string $mobile): static
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getSabPaisaAttributes(): array
    {
        return [
            'payerName' => $this->name,
            'payerEmail' => $this->email,
            'payerMobile' => $this->mobile,
            'payerAddress' => $this->address,
        ];
    }

    public function getCcAvenueAttributes(): array
    {
        return [
            'billing_name' => $this->name,
            'billing_email' => $this->email,
            'billing_tel' => $this->mobile,
            'billing_address' => $this->address,
        ];
    }

    public function getCashfreeCustomer(): CustomerDetails
    {
        $customerDetails = new CustomerDetails();
        $customerDetails->setCustomerId(str_pad($this->model->getKey(),8,rand()));
        $customerDetails->setCustomerName($this->name);
        $customerDetails->setCustomerEmail($this->email);
        $customerDetails->setCustomerPhone($this->mobile);

        return $customerDetails;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'address' => $this->address,
            'model' => $this->model->toArray(), 
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }
}
