<?php

declare(strict_types=1);

namespace OpsWay\YesBank;

interface TransportInterface
{
    public function sendPost(string $endpoint, $data): object;

    public function getConfig(): Config;
}
