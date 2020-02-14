<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api\Dto;

class MaintainBeneficiaryResponseDto
{
    /**
     * @var string
     */
    public string $requestStatus;

    /**
     * @var string
     */
    public string $reqRefNo;

    /**
     * @var string
     */
    public string $custId;

    /**
     * @var string
     */
    public string $srcAccountNo;

    /**
     * @var string
     */
    public string $beneficiaryCd;

    /**
     * @var string
     */
    public string $beneName;

    /**
     * @var string
     */
    public string $beneType;

    /**
     * @var string
     */
    public string $beneAccountNo;

    /**
     * @var string
     */
    public string $bankName;

    /**
     * @var string
     */
    public string $ifscCode;

    /**
     * @var float
     */
    public float $transactionLimit;

    /**
     * @var string
     */
    public string $currencyCd;

    /**
     * @var string
     */
    public string $action;

    /**
     * @var object
     */
    public object $error;
}
