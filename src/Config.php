<?php

declare(strict_types=1);

namespace OpsWay\YesBank;

class Config
{
    protected string $baseUrl;
    protected string $clientId;
    protected string $clientSecret;
    protected string $customerId;
    protected string $appId;
    protected ?string $basicAuthLogin = null;
    protected ?string $basicAuthPass = null;
    protected ?string $sslCert = null;
    protected ?string $sslKey = null;
    protected ?string $caCert = null;

    public function __construct(
        string $baseUrl,
        string $clientId,
        string $clientSecret,
        string $customerId,
        string $appId
    ) {
        $this->baseUrl = $baseUrl;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->customerId = $customerId;
        $this->appId = $appId;
    }

    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    public function setBaseUrl(string $baseUrl): self
    {
        $this->baseUrl = $baseUrl;

        return $this;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function setClientId(string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(string $secret): self
    {
        $this->clientSecret = $secret;

        return $this;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function setAppId(string $appId): self
    {
        $this->appId = $appId;

        return $this;
    }

    public function getBasicAuthLogin(): ?string
    {
        return $this->basicAuthLogin;
    }

    public function setBasicAuthLogin(?string $authLogin): self
    {
        $this->basicAuthLogin = $authLogin;

        return $this;
    }

    public function getBasicAuthPassword(): ?string
    {
        return $this->basicAuthPass;
    }

    public function setBasicAuthPassword(?string $authPass): self
    {
        $this->basicAuthPass = $authPass;

        return $this;
    }

    public function getSslCert(): ?string
    {
        return $this->sslCert;
    }

    public function setSslCert(?string $cert): self
    {
        $this->sslCert = $cert;

        return $this;
    }

    public function getSslKey(): ?string
    {
        return $this->sslKey;
    }

    public function setSslKey(?string $key): self
    {
        $this->sslKey = $key;

        return $this;
    }

    public function getCaCert(): ?string
    {
        return $this->caCert;
    }

    public function setCaCert(?string $caCert): self
    {
        $this->caCert = $caCert;

        return $this;
    }
}
