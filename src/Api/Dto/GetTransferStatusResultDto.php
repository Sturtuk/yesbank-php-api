<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class GetTransferStatusResultDto
{
    /**
     * @var string
     */
    public string $version;

    /**
     * @var string
     */
    public string $transferType;

    /**
     * @var string
     */
    public string $reqTransferType;

    /**
     * @var \DateTime
     */
    public \DateTime $transactionDate;

    /**
     * @var float
     */
    public float $transferAmount;

    /**
     * @var string
     */
    public string $transferCurrencyCode;

    /**
     * @var TransactionStatusDto
     */
    public TransactionStatusDto $transactionStatus;
}
