<?php

namespace DigitalTunnel\HyperPay\Services;

use DigitalTunnel\HyperPay\Contracts\HyperPay;
use DigitalTunnel\HyperPay\Interfaces\BackofficeInterface;
use DigitalTunnel\HyperPay\Traits\Processor;

class Backoffice extends HyperPay implements BackofficeInterface
{
    use Processor;

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
     * set amount to ['config'].
     */
    public function setAmount(string $amount): static
    {
        $this->config['amount'] = $amount;

        return $this;
    }

    /**
     * set currency to ['config'].
     * optional, if not set, the default currency will be used.
     */
    public function setCurrency(?string $currency = null): static
    {
        $this->config['currency'] = $currency;

        return $this;
    }

    /**
     * set checkout ID to ['config'].
     */
    public function setCheckoutId(string $checkoutId): static
    {
        $this->config['checkout_id'] = $checkoutId;

        return $this;
    }

    /**
     * process refund .
     */
    public function processRefund(): array
    {
        return $this->response(
            response: $this->refund(
                checkoutId: $this->config['checkout_id'],
            )
        );
    }

    /**
     * process reverse .
     */
    public function processReverse(): array
    {
        return $this->response(
            response: $this->reverse(
                checkoutId: $this->config['checkout_id'],
            )
        );
    }

    /**
     * get refund or reverse response.
     */
    private function response(array $response): array
    {
        $default = [
            'payment_method' => $this->config['payment_method'],
            'test_mode' => $this->isTestMode,
            'success' => $this->validateStatus($response['result']['code']),
            'message' => $response['result']['description'],
        ];

        return collect($response)->merge($default)->toArray();
    }
}
