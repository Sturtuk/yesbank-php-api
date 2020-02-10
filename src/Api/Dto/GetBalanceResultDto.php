<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class GetBalanceResultDto
{
    /**
     * @var string
     */
    public string $version;

    /**
     * @var string
     */
    public string $accountCurrencyCode;

    /**
     * @var float
     */
    public float $accountBalanceAmount;

    /**
     * @var bool
     */
    public bool $lowBalanceAlert;
}
