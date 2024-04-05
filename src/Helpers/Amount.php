<?php

namespace DigitalTunnel\HyperPay\Helpers;

class Amount
{
    public static function format(string $amount): string
    {
        if (str_contains($amount, '.')) {
            $parts = explode('.', $amount);
            // If there's only one digit after the decimal point, add a '0'
            if (strlen($parts[1]) == 1) {
                $amount .= '0';
            }
        } else {
            // If there's no decimal point, add '.00'
            $amount .= '.00';
        }

        $formatted_amount = number_format($amount, 2);

        return str_replace(',', '', $formatted_amount);
    }
}
