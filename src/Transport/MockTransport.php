<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Transport;

use OpsWay\YesBank\Config;
use OpsWay\YesBank\TransportInterface;

use function GuzzleHttp\json_decode;

class MockTransport implements TransportInterface
{
    protected string $jsonResponse;

    public function __construct(string $jsonResponse = '{}')
    {
        $this->jsonResponse = $jsonResponse;
    }

    public function sendPost(string $endpoint, $data): object
    {
        return json_decode($this->jsonResponse);
    }

    public function getConfig(): Config
    {
        return new Config(true, '', '', '', '');
    }
}
