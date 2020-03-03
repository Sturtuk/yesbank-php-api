<?php

declare(strict_types=1);

namespace OpsWay\YesBank;

use OpsWay\YesBank\Api\FundTransfer;
use OpsWay\YesBank\Api\MaintainBeneficiary;

class Api
{
    protected TransportInterface $transport;

    public function __construct(TransportInterface $transport)
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
     * @return MaintainBeneficiary
     */
    public function maintainBeneficiary(): MaintainBeneficiary
    {
        return new MaintainBeneficiary($this);
    }

    /**
     * @return TransportInterface
     */
    public function getTransport(): TransportInterface
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
