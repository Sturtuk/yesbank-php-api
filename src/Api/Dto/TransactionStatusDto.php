<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class TransactionStatusDto
{
    public const STATUS_SENT       = 'SENT_TO_BENEFICIARY';
    public const STATUS_IN_PROCESS = 'IN_PROCESS';
    public const STATUS_COMPLETED  = 'COMPLETED';
    public const STATUS_FAILED     = 'FAILED';
    public const STATUS_RETURNED   = 'RETURNED_FROM_BENEFICIARY';
    public const STATUS_ON_HOLD    = 'ON_HOLD';

    /**
     * @var string
     */
    public string $statusCode;

    /**
     * @var string
     */
    public string $subStatusCode;

    /**
     * @var string|null
     */
    public ?string $bankReferenceNo;

    /**
     * @var string
     */
    public string $beneficiaryReferenceNo;
}
