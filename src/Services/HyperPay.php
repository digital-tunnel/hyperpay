<?php

namespace DigitalTunnel\HyperPay\Services;

use DigitalTunnel\HyperPay\Data\CustomerData;
use DigitalTunnel\HyperPay\Enums\PaymentMethod;
use DigitalTunnel\HyperPay\Exceptions\InvalidPaymentMethod;
use Exception;
use Illuminate\Support\Number;

class HyperPay
{
    private string $paymentMethod;

    private string $transactionId;

    private string $checkoutId;

    private string $currency;

    private array $customer;

    private array $registrations;

    private string $amount;

    private string $fromDate;

    private string $toDate;

    /**
     * Set Payment Method to ['config'].
     */
    public function setMethod(string $paymentMethod): self
    {
        $method = PaymentMethod::tryFrom(
            value: strtolower($paymentMethod)
        );

        $this->paymentMethod = $method->value;

        return $this;
    }

    /**
     * Set Transaction ID to ['config'].
     */
    public function setTransactionId(string $transactionId): self
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    /**
     * Set Customer to ['config'].
     */
    public function setCustomer(CustomerData $customer): self
    {
        $this->customer = (array) $customer;

        return $this;
    }

    /**
     * Set Registrations to be used in the OneClick checkout.
     */
    public function setRegistrations(array $registrations): self
    {
        $this->registrations = $registrations;

        return $this;
    }

    /**
     * set currency to ['config'].
     */
    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * set amount to ['config'].
     */
    public function setAmount(string $amount): self
    {
        $this->amount = Number::format($amount, maxPrecision: 2);

        return $this;
    }

    /**
     * set checkout ID.
     */
    public function setCheckoutId(string $checkoutId): self
    {
        $this->checkoutId = $checkoutId;

        return $this;
    }

    /**
     * set From Date to ['config'].
     *
     * @return $this
     */
    public function setFromDate(string $fromDate): self
    {
        $this->fromDate = $fromDate;

        return $this;
    }

    /**
     * Set To Date to ['config'].
     *
     * @return $this
     */
    public function setToDate(string $toDate): self
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Create Checkout Request.
     *
     * @throws Exception
     */
    public function checkout(): array
    {
        return (new PrepareCheckout(['payment_method' => $this->paymentMethod]))
            ->setTransactionId($this->transactionId)
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setCustomer($this->customer)
            ->checkout();
    }

    /**
     * Create Checkout Request.
     *
     * @throws Exception
     */
    public function checkoutWithTokenization(): array
    {
        return (new PrepareCheckout(['payment_method' => $this->paymentMethod]))
            ->setTransactionId($this->transactionId)
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setCustomer($this->customer)
            ->tokenizationCheckout();
    }

    /**
     * Create Checkout Request.
     *
     * @throws Exception
     */
    public function oneClickCheckout(): array
    {
        return (new PrepareCheckout(['payment_method' => $this->paymentMethod]))
            ->setTransactionId($this->transactionId)
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setRegistrations($this->registrations)
            ->oneClickCheckout();
    }

    /**
     * Validate Payment Status
     *
     * @throws Exception
     */
    public function getStatus(): array
    {
        return (new PaymentStatus(['payment_method' => $this->paymentMethod]))
            ->setCheckoutId($this->checkoutId)
            ->getStatus();
    }

    /**
     * Get Transaction Report
     *
     * @throws Exception
     */
    public function getTransactionReport(): array
    {
        return (new TransactionReport(['payment_method' => $this->paymentMethod]))
            ->setCheckoutId($this->checkoutId)
            ->getTransactionReport();
    }

    /**
     * get settlement report
     *
     * @throws InvalidPaymentMethod
     * @throws Exception
     */
    public function getSettlement(): array
    {
        return (new SettlementReport(['payment_method' => $this->paymentMethod]))
            ->setFromDate($this->fromDate)
            ->setToDate($this->toDate)
            ->getSettlement();
    }

    /**
     * refund transaction
     *
     * @throws InvalidPaymentMethod
     */
    public function refund(): array
    {
        return (new Backoffice(['payment_method' => $this->paymentMethod]))
            ->setAmount($this->amount)
            ->setCurrency($this->currency)
            ->setCheckoutId($this->checkoutId)
            ->processRefund();

    }

    /**
     * reverse transaction
     *
     * @throws InvalidPaymentMethod
     */
    public function reverse(): array
    {
        return (new Backoffice(['payment_method' => $this->paymentMethod]))
            ->setCheckoutId($this->checkoutId)
            ->processReverse();

    }
}
