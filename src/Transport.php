<?php

declare(strict_types=1);

namespace OpsWay\YesBank;

use GuzzleHttp\Client as BaseClient;
use GuzzleHttp\ClientInterface;
use OpsWay\YesBank\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

class Transport
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
     * @throws InvalidJsonException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendPost(string $endpoint, $data): object
    {
        $options = [
            'allow_redirects' => true,
            'timeout' => 10,
            'headers' => [
                'Content-Type' => 'application/json',
                'X-IBM-Client-Id' => $this->config->getClientId(),
                'X-IBM-Client-Secret' => $this->config->getClientSecret(),
            ],
            'body' => json_encode($data),
            // ToDo: Certificates
        ];

        if ($this->config->getBasicAuthLogin() !== null && $this->config->getBasicAuthPassword() !== null) {
            $options['auth'] = [$this->config->getBasicAuthLogin(), $this->config->getBasicAuthPassword()];
        }

        return $this->processResponse($this->httpClient->request('POST', $this->prepareEndpoint($endpoint), $options));
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
            throw new ApiException('Invalid JSON response', 503);
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
        $code = $faultData->Code->Subcode->Subcode->Value ?? null;
        $message = $faultData->Reason->Text ?? 'Unexpected error';
        $details = $faultData->Detail->MessageList ?? 'Failed to parse API error. Please check problem manually.';

        return new ApiException(sprintf('[%s] %s: %s', $code, $message, $details));
    }
}
