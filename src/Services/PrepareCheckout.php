<?php

namespace DigitalTunnel\HyperPay\Services;

use Exception;
use DigitalTunnel\HyperPay\Contracts\HyperPay;
use DigitalTunnel\HyperPay\Interfaces\CheckoutInterface;
use DigitalTunnel\HyperPay\Traits\Processor;
use Illuminate\Support\Arr;
use Illuminate\Support\Number;

class PrepareCheckout extends HyperPay implements CheckoutInterface
{
    use Processor;

    /**
     * Initialize API Processor Constructor.
     * Accept All hyperpay API Parameters.
     *
     * @throws Exception
     */
    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * set payment method to ['config'].
     */
    public function setMethod(string $paymentMethod): static
    {
        $this->config['payment_method'] = $paymentMethod;

        return $this;
    }

    /**
     * set transaction ID to ['config'].
     * required for backoffice operations.
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->config['merchantTransactionId'] = $transactionId;

        return $this;
    }

    /**
     * set currency to ['config'].
     * optional, if not set, the default currency will be used.
     */
    public function setCurrency(string $currency = null): static
    {
        $this->config['currency'] = $currency;

        return $this;
    }

    /**
     * Set amount to ['config'].
     */
    public function setAmount(string $amount): static
    {
        $this->config['amount'] = $amount;

        return $this;
    }

    /**
     * set customer to ['config'].
     */
    public function setCustomer(array $customer): static
    {
        $this->config['customer'] = $customer;

        return $this;
    }

    /**
     * set registrations IDs ['config'] to be used in one click checkout.
     */
    public function setRegistrations(array $registrations = null): static
    {
        Arr::map($registrations, function ($value, $key) {
            return $this->config['registrations'.'['.$key.']']['id'] = $value;
        });

        return $this;
    }

    /**
     * process tokenized checkout.
     */
    public function tokenizationCheckout(): array
    {
        $this->withTokenization();

        return $this->responseHandler();
    }

    /**
     * process one click checkout.
     */
    public function oneClickCheckout(): array
    {
        $this->withOneClick();

        return $this->responseHandler();
    }

    /**
     * submit checkout request to hyperpay api.
     */
    public function checkout(): array
    {
        $this->withBasic();

        return $this->responseHandler();
    }

    /**
     * process checkout request.
     */
    private function responseHandler(): array
    {
        return $this->response(
            response: $this->PrepareCheckout(),
        );
    }

    /**
     * api response.
     */
    private function response(array $response): array
    {
        $default = [
            'merchant_transaction_id' => $this->config['merchantTransactionId'],
            'payment_method' => $this->config['payment_method'],
            'test_mode' => $this->isTestMode,
            'endpoint' => $this->endpoint,
            'script_url' => isset($response['id']) ? $this->endpoint.'/v1/paymentWidgets.js?checkoutId='.$response['id'] : null,
            'currency' => $this->config['currency'],
            'amount' => $this->config['amount'],
            'success' => $this->validateCheckout($response['result']['code']),
        ];
        return collect($response)->merge($default)->toArray();
    }

    /**
     * tokenization checkout.
     * this will return registration id after checkout.
     * you can use this registration id to make one click checkout.
     */
    protected function withTokenization(): void
    {
        $this->config['createRegistration'] = true;
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
        ];
    }

    /**
     * basic checkout configuration.
     */
    protected function withBasic(): void
    {
        $this->config['paymentType'] = 'DB';
    }

    /**
     * one click configuration.
     * required array of registration ids.
     */
    protected function withOneClick(): void
    {
        $this->config['standingInstruction'] = [
            'source' => 'CIT',
            'mode' => 'INITIAL',
            'type' => 'UNSCHEDULED',
        ];
    }
}
