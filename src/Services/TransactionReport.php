<?php

namespace DigitalTunnel\HyperPay\Services;

use DigitalTunnel\HyperPay\Contracts\HyperPay;
use DigitalTunnel\HyperPay\Interfaces\PaymentReportInterface;
use DigitalTunnel\HyperPay\Traits\Processor;
use Exception;

class TransactionReport extends HyperPay implements PaymentReportInterface
{
    use Processor;

    public function __construct(array $config)
    {
        parent::__construct($config);
    }

    /**
     * set payment method.
     */
    public function setMethod(string $paymentMethod): static
    {
        $this->config['payment_method'] = $paymentMethod;

        return $this;
    }

    /**
     * set checkout id.
     */
    public function setCheckoutId(string $checkoutId): static
    {
        $this->config['checkout_id'] = $checkoutId;

        return $this;
    }

    /**
     * Submit Payment Status Request to hyperpay API.
     *
     * @throws Exception
     */
    public function getTransactionReport(): array
    {
        return $this->process();
    }

    /**
     * process settlement report.
     *
     * @throws Exception
     */
    private function process(): array
    {
        return $this->response(
            response: $this->TransactionReport(
                checkoutId: $this->config['checkout_id'],
            )
        );
    }

    /**
     *  payment status response.
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
