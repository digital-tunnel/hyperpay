<?php

namespace DigitalTunnel\HyperPay\Services;

class Response
{
    private array $response;

    public function __construct(...$response)
    {
        $this->response = $response;
    }

    public function getResponse(): array
    {
        return collect($this->response)->ea;
    }
}
