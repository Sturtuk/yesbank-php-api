<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Api;

use JsonMapper;
use JsonMapper_Exception;
use GuzzleHttp\Exception\GuzzleException;
use OpsWay\YesBank\Api;
use OpsWay\YesBank\Api\Dto\BankDto;
use OpsWay\YesBank\Api\Dto\BeneficiaryDto;
use OpsWay\YesBank\Config;
use OpsWay\YesBank\Exception\ApiException;

use function GuzzleHttp\json_decode;

class MaintainBeneficiary
{
    protected const ENDPOINT_BENE = '/BeneMaintenanceMobileService/maintainBene';
    protected const REQUEST_STATUS_SUCCESS = 'SUCCESS';

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
     * @param string $sourceAccountNo
     * @param BeneficiaryDto $beneficiary
     * @param BankDto $bank
     * @param float $amount
     * @param string $currency
     * @param string $paymentType
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function add(
        string $sourceAccountNo,
        BeneficiaryDto $beneficiary,
        BankDto $bank,
        float $amount,
        string $currency = 'INR',
        string $paymentType = 'OTHR'
    ): Dto\MaintainBeneficiaryResponseDto {
        return $this->sendRequest('ADD', $sourceAccountNo, $beneficiary, $bank, $amount, $currency, $paymentType);
    }

    /**
     * @param string $sourceAccountNo
     * @param BeneficiaryDto $beneficiary
     * @param BankDto $bank
     * @param float $amount
     * @param string $currency
     * @param string $paymentType
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function modify(
        string $sourceAccountNo,
        BeneficiaryDto $beneficiary,
        BankDto $bank,
        float $amount,
        string $currency = 'INR',
        string $paymentType = 'OTHR'
    ): Dto\MaintainBeneficiaryResponseDto {
        return $this->sendRequest('MODIFY', $sourceAccountNo, $beneficiary, $bank, $amount, $currency, $paymentType);
    }

    /**
     * @param string $sourceAccountNo
     * @param BeneficiaryDto $beneficiary
     * @param BankDto $bank
     * @param float $amount
     * @param string $currency
     * @param string $paymentType
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function verify(
        string $sourceAccountNo,
        BeneficiaryDto $beneficiary,
        BankDto $bank,
        float $amount,
        string $currency = 'INR',
        string $paymentType = 'OTHR'
    ): Dto\MaintainBeneficiaryResponseDto {
        return $this->sendRequest('VERIFY', $sourceAccountNo, $beneficiary, $bank, $amount, $currency, $paymentType);
    }

    /**
     * @param string $sourceAccountNo
     * @param string $beneficiaryCode
     * @param string $paymentType
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    public function disable(
        string $sourceAccountNo,
        string $beneficiaryCode,
        string $paymentType = 'OTHR'
    ): Dto\MaintainBeneficiaryResponseDto {
        $rawResult = $this->api->getTransport()->sendPost(
            self::ENDPOINT_BENE,
            ['maintainBene' => [
                'CustId' => $this->config->getCustomerId(),
                'BeneficiaryCd' => $beneficiaryCode,
                'SrcAccountNo' => $sourceAccountNo,
                'PaymentType' => $paymentType,
                'Action' => 'DISABLE',
            ]],
        );

        return $this->processResult($rawResult);
    }

    /**
     * @param string $actionType
     * @param string $sourceAccountNo
     * @param BeneficiaryDto $beneficiary
     * @param BankDto $bank
     * @param float $amount
     * @param string $currency
     * @param string $paymentType
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws GuzzleException
     * @throws JsonMapper_Exception
     */
    protected function sendRequest(
        string $actionType,
        string $sourceAccountNo,
        BeneficiaryDto $beneficiary,
        BankDto $bank,
        float $amount,
        string $currency = 'INR',
        string $paymentType = 'OTHR'
    ): Dto\MaintainBeneficiaryResponseDto {
        $rawResult = $this->api->getTransport()->sendPost(
            self::ENDPOINT_BENE,
            ['maintainBene' => [
                'CustId' => $this->config->getCustomerId(),
                'BeneficiaryCd' => $beneficiary->code,
                'SrcAccountNo' => $sourceAccountNo,
                'PaymentType' => $paymentType,
                'BeneName' => $beneficiary->name,
                'BeneType' => $beneficiary->type,
                'CurrencyCd' => $currency,
                'TransactionLimit' => $amount,
                'BankName' => $bank->name,
                'IfscCode' => $bank->ifscCode,
                'BeneAccountNo' => $beneficiary->accountNo,
                'Action' => $actionType,
            ]],
        );

        return $this->processResult($rawResult);
    }

    /**
     * @param object $rawResult
     * @return Dto\MaintainBeneficiaryResponseDto
     * @throws ApiException
     * @throws JsonMapper_Exception
     */
    protected function processResult(object $rawResult): Dto\MaintainBeneficiaryResponseDto
    {
        if (!isset($rawResult->maintainBeneResponse)) {
            throw new ApiException('Invalid response: not found "maintainBeneResponse" field');
        }

        /** @var Dto\MaintainBeneficiaryResponseDto $result */
        $result = $this->jsonMapper->map($rawResult->maintainBeneResponse, new Dto\MaintainBeneficiaryResponseDto);

        if ($result->requestStatus !== self::REQUEST_STATUS_SUCCESS) {
            throw $this->convertErrorToException($result->error);
        }

        return $result;
    }

    /**
     * @param object $faultData
     * @return ApiException
     */
    protected function convertErrorToException(object $faultData): ApiException
    {
        $faultData = $faultData->Error ?? '';
        $errors = json_decode($faultData);
        $error = array_shift($errors);

        $code = $error->ErrorSubCode ?? 0;
        $message = $error->GeneralMsg ?? 'Unexpected error';

        return new ApiException(sprintf('[Error %s] %s', $code, $message));
    }
}
