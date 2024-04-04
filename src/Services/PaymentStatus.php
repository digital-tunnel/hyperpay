<?php

namespace DigitalTunnel\HyperPay\Services;

use Exception;
use DigitalTunnel\HyperPay\Contracts\HyperPay;
use DigitalTunnel\HyperPay\Interfaces\PaymentStatusInterface;
use DigitalTunnel\HyperPay\Traits\Processor;

class PaymentStatus extends HyperPay implements PaymentStatusInterface
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
     * set checkout ID to ['config'].
     */
    public function setCheckoutId(string $checkoutId): static
    {
        $this->config['checkout_id'] = $checkoutId;

        return $this;
    }

    /**
     * submit payment status request to hyperpay API.
     *
     * @throws Exception
     */
    public function getStatus(): array
    {
        return $this->process();
    }

    /**
     * Initiate Payment Status Request.
     */
    private function process(): array
    {
        return $this->response(
            response: $this->PaymentStatus(
                checkoutId: $this->config['checkout_id'],
            )
        );
    }

    /**
     * Render hyperpay API Response.
     */
    private function response(array $response): array
    {
        return [
            'response' => $response,
            'props' => [
                'payment_method' => $this->config['payment_method'],
                'test_mode' => $this->isTestMode,
                'status' => [
                    'success' => $this->validateStatus($response['result']['code']),
                    'message' => $response['result']['description'],
                ],
            ],
        ];
    }
}
