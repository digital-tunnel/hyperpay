<?php

namespace DigitalTunnel\HyperPay\Exceptions;

use Exception;
use Illuminate\Http\Response;

class InvalidPaymentMethod extends Exception
{
    public function render(): Response
    {
        return response([
            'error' => 'Invalid payment method, please check the documentation.',
            'help' => 'Payment method should be one of: visa - mastercard - mada - applepay'], 400);
    }
}
