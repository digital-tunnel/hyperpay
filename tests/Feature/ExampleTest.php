<?php

use DigitalTunnel\HyperPay\Data\CustomerData;
use DigitalTunnel\HyperPay\Facades\HyperPay;

test(/**
 * @throws Exception
 */ 'example', function () {
    $response = HyperPay::setMethod('visa')
        ->setTransactionId('')->setCurrency('SAR')
        ->setAmount('100.00')
        ->setCustomer(new CustomerData(
            givenName: 'John',
            surname: 'Doe',
            mobile: '0567339795',
            email: '',
            merchantCustomerId: 1
        ))->checkout();

    ray($response);
});
