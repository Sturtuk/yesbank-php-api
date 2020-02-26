<?php

declare(strict_types=1);

namespace OpsWay\YesBank\Exception;

use Throwable;

class ApiException extends \Exception
{
    protected string $apiCode;
    protected string $details;

    public function __construct(
        string $message = '',
        string $code = '',
        string $details = '',
        Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->apiCode = $code;
        $this->details = $details;
    }

    public function getApiCode() : string
    {
        return $this->apiCode;
    }

    public function getDetails() : string
    {
        return $this->details;
    }

    public function __toString() : string
    {
        return (!empty($this->apiCode) ? '[Error ' . $this->apiCode . '] ' : '') .
            $this->message .
            (!empty($this->details) ? ': ' . $this->details : '');
    }
}
