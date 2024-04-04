<?php

namespace DigitalTunnel\HyperPay\Data;

readonly class CustomerData
{
    public function __construct(
        public string  $givenName,
        public string  $surname,
        public string  $mobile,
        public ?string $email = null,
        public ?string $merchantCustomerId = null,
    ) {
    }
}
