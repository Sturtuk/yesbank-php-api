<?php

declare(strict_types=1);

namespace OpsWay\YesBank;

use OpsWay\YesBank\Api\FundTransfer;

class Api
{
    protected Transport $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * @return FundTransfer
     */
    public function fundTransfer(): FundTransfer
    {
        return new FundTransfer($this);
    }

    /**
     * @return Transport
     */
    public function getTransport(): Transport
    {
        return $this->transport;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->transport->getConfig();
    }
}
