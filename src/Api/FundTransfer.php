<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api;

use JsonMapper;
use JsonMapper_Exception;
use OpsWay\YesBank\Api;
use OpsWay\YesBank\Api\Dto;
use OpsWay\YesBank\Config;
use OpsWay\YesBank\Exception\ApiException;

class FundTransfer
{
    protected const ENDPOINT_GET_BALANCE_PROD = '/fundsTransferServiceRS2/getBalance';
    protected const ENDPOINT_GET_BALANCE_UAT = '/fundtransfer2R/getbalance';
    protected const ENDPOINT_START_TRANSFER_PROD = '/fundsTransferServiceRS2/startTransfer';
    protected const ENDPOINT_START_TRANSFER_UAT = '/fundtransfer2R/startTransfer';
    protected const ENDPOINT_GET_STATUS_PROD = '/fundsTransferServiceRS2/getStatus';
    protected const ENDPOINT_GET_STATUS_UAT = '/fundtransfer2R/getstatus';

    protected Api $api;
    protected Config $config;
    protected JsonMapper $jsonMapper;

    public function __construct(Api $api)
    {
        $this->api = $api;
        $this->config = $api->getConfig();
        $this->jsonMapper = new JsonMapper;
    }

    /**
     * @param string $accountNumber
     * @return Dto\GetBalanceResultDto
     * @throws ApiException
     * @throws JsonMapper_Exception
     */
    public function getBalance(string $accountNumber): Dto\GetBalanceResultDto
    {
        $rawResult = $this->api->getTransport()->sendPost(
            ($this->config->isProdMode() ? self::ENDPOINT_GET_BALANCE_PROD : self::ENDPOINT_GET_BALANCE_UAT),
            ['getBalance' => [
                'version' => 2,
                'appID' => $this->config->getAppId(),
                'customerID' => $this->config->getCustomerId(),
                'AccountNumber' => $accountNumber,
            ]],
        );

        if (!isset($rawResult->getBalanceResponse)) {
            throw new ApiException('Invalid response: not found "getBalanceResponse" field');
        }

        /** @var Dto\GetBalanceResultDto $result */
        $result = $this->jsonMapper->map($rawResult->getBalanceResponse, new Dto\GetBalanceResultDto);

        return $result;
    }

    /**
     * @param string $requestNo
     * @param string $accountNo
     * @param Dto\BeneficiaryDto $beneficiary
     * @param float $amount
     * @param string $transferType
     * @param string $currencyCode
     * @param string $purposeCode
     * @param string $remitterToBeneficiaryInfo
     * @return Dto\StartTransferResultDto
     * @throws ApiException
     * @throws JsonMapper_Exception
     */
    public function startTransfer(
        string $requestNo,
        string $accountNo,
        Dto\BeneficiaryDto $beneficiary,
        float $amount,
        string $transferType = 'ANY',
        string $currencyCode = 'INR',
        string $purposeCode = 'NODAL',
        string $remitterToBeneficiaryInfo = 'FUND TRANSFER'
    ): Dto\StartTransferResultDto {
        $rawResult = $this->api->getTransport()->sendPost(
            ($this->config->isProdMode() ? self::ENDPOINT_START_TRANSFER_PROD : self::ENDPOINT_START_TRANSFER_UAT),
            ['startTransfer' => [
                'version' => '1',
                'uniqueRequestNo' => $requestNo,
                'appID' => $this->config->getAppId(),
                'purposeCode' => $purposeCode,
                'customerID' => $this->config->getCustomerId(),
                'debitAccountNo' => $accountNo,
                'beneficiary' => [
                    'beneficiaryCode' => $beneficiary->code,
                ],
                'transferType' => $transferType,
                'transferCurrencyCode' => $currencyCode,
                'transferAmount' => $amount,
                'remitterToBeneficiaryInfo' => $remitterToBeneficiaryInfo,
            ]],
        );

        if (!isset($rawResult->startTransferResponse)) {
            throw new ApiException('Invalid response: not found "startTransferResponse" field');
        }

        /** @var Dto\StartTransferResultDto $result */
        $result = $this->jsonMapper->map($rawResult->startTransferResponse, new Dto\StartTransferResultDto);

        return $result;
    }

    /**
     * @param string $requestNo
     * @return Dto\GetTransferStatusResultDto
     * @throws ApiException
     * @throws JsonMapper_Exception
     */
    public function getTransferStatus(string $requestNo): Dto\GetTransferStatusResultDto
    {
        $rawResult = $this->api->getTransport()->sendPost(
            ($this->config->isProdMode() ? self::ENDPOINT_GET_STATUS_PROD : self::ENDPOINT_GET_STATUS_UAT),
            ['getStatus' => [
                'version' => '1.0',
                'appID' => $this->config->getAppId(),
                'customerID' => $this->config->getCustomerId(),
                'requestReferenceNo' => $requestNo,
            ]],
        );

        if (!isset($rawResult->getStatusResponse)) {
            throw new ApiException('Invalid response: not found "getStatusResponse" field');
        }

        /** @var Dto\GetTransferStatusResultDto $result */
        $result = $this->jsonMapper->map($rawResult->getStatusResponse, new Dto\GetTransferStatusResultDto);

        return $result;
    }
}
