<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class StartTransferResultDto
{
    /**
     * @var string
     */
    public string $version;

    /**
     * @var string
     */
    public string $requestReferenceNo;

    /**
     * @var string
     */
    public string $uniqueResponseNo;

    /**
     * @var int
     */
    public int $attemptNo;

    /**
     * @var string
     */
    public string $reqTransferType;

    /**
     * @var string
     */
    public string $statusCode;
}
