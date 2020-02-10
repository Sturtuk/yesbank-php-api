<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class TransactionStatusDto
{
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
