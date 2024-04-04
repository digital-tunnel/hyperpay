<?php

namespace DigitalTunnel\HyperPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class HyperPayFacade
 *
 * @mixin \DigitalTunnel\HyperPay\Services\HyperPay
 *
 */
class HyperPay extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'hyperpay';
    }
}
