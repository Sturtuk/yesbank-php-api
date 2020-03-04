<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Transport;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use OpsWay\YesBank\Config;
use OpsWay\YesBank\Exception\ApiException;
use OpsWay\YesBank\Exception\Common as CommonException;
use OpsWay\YesBank\TransportInterface;
use Psr\Http\Message\ResponseInterface;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class HttpTransport implements TransportInterface
{
    protected ClientInterface $httpClient;
    protected Config $config;

    public function __construct(Config $config)
    {
        $this->httpClient = new BaseClient();
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @param string $endpoint
     * @param $data
     * @return object
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendPost(string $endpoint, $data): object
    {
        $options = [
            'allow_redirects' => true,
            'http_errors' => false,
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-IBM-Client-Id' => $this->config->getClientId(),
                'X-IBM-Client-Secret' => $this->config->getClientSecret(),
            ],
            'body' => json_encode($data),
        ];

        if ($sslCert = $this->config->getSslCert()) {
            $options['cert'] = $sslCert;
        }

        if ($sslKey = $this->config->getSslKey()) {
            $options['ssl_key'] = $sslKey;
        }

        if ($this->config->getBasicAuthLogin() !== null && $this->config->getBasicAuthPassword() !== null) {
            $options['auth'] = [$this->config->getBasicAuthLogin(), $this->config->getBasicAuthPassword()];
        }

        $response = $this->httpClient->request('POST', $this->prepareEndpoint($endpoint), $options);
        if ($response->getStatusCode() === 200) {
            return $this->processResponse($response);
        }

        switch ($response->getStatusCode()) {
            case 401:
                throw new CommonException\UnauthorizedException(
                    $response->getReasonPhrase(),
                    'http:' . $response->getStatusCode()
                );
        }

        throw new CommonException\ServerErrorException($response->getReasonPhrase());
    }

    /**
     * @param ResponseInterface $response
     * @return object
     * @throws ApiException
     */
    protected function processResponse(ResponseInterface $response): object
    {
        try {
            $responseObject = json_decode($response->getBody());
        } catch (\Throwable $e) {
            throw new ApiException('Invalid JSON response');
        }

        if (isset($responseObject->Fault)) {
            throw $this->convertErrorToException($responseObject->Fault);
        }

        return $responseObject;
    }

    /**
     * @param string $endpoint
     * @return string
     */
    protected function prepareEndpoint(string $endpoint): string
    {
        return rtrim($this->config->getBaseUrl(), '/') . $endpoint;
    }

    /**
     * @param object $faultData
     * @return ApiException
     */
    protected function convertErrorToException(object $faultData): ApiException
    {
        $code = $faultData->Code->Subcode->Value ?? '';
        $subCode = $faultData->Code->Subcode->Subcode->Value ?? '';
        $faultCode = $code . ((!empty($subCode)) ? '-' . $subCode : '');

        $message = $faultData->Reason->Text ?? 'Unknown error';
        $details = $faultData->Detail->MessageList ?? '';

        switch ($code) {
            case 'ns:E400':
                return new CommonException\BadRequestException($message, $faultCode, $details);
            case 'ns:E402':
                return new CommonException\InsufficientBalanceException($message, $faultCode, $details);
            case 'ns:E403':
                return new CommonException\ForbiddenException($message, $faultCode, $details);
            case 'ns:E405':
                return new CommonException\NotAllowedTransferTypeException($message, $faultCode, $details);
            case 'ns:E406':
                return new CommonException\BeneficiaryNotAcceptable($message, $faultCode, $details);
            case 'ns:E429':
                return new CommonException\DailyLimitExceededException($message, $faultCode, $details);
        }

        return new ApiException($message, $faultCode, $details);
    }
}
