<?php

namespace DigitalTunnel\HyperPay\Enums;

enum PaymentMethod: string
{
    case Mada = 'mada';
    case Visa = 'visa';
    case MasterCard = 'mastercard';
    case ApplePay = 'applepay';
}
