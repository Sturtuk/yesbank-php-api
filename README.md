# YesBank API - PHP SDK Library

## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run:
```
composer require opsway/yesbank-api
```

## Usage

First of all, you should to prepare API client with your parameters:
```php
use OpsWay\YesBank\Api;
use OpsWay\YesBank\Config;
use OpsWay\YesBank\Transport;

// Prepare config for connection
$config = new Config('https://uatsky.yesbank.in/app/uat', '<CLIENT_ID>', '<SECRET>', '<CUSTOMER_ID>', '<APP_ID>');
$config->setBasicAuthLogin('testclient');       // Optional
$config->setBasicAuthPassword('OxYcool@123');   // Optional

$transport = new Transport($config);
$api = new Api($transport);
```

### Get Balance
```php
$balance = $api->fundTransfer()->getBalance('<ACCOUNT_NUMBER>');

// Result: OpsWay\YesBank\Api\Dto\GetBalanceResultDto Object
// (
//     [version] => 2.0
//     [accountCurrencyCode] => INR
//     [accountBalanceAmount] => 6.0793648885606E+21
//     [lowBalanceAlert] => false
// )
```

### Start Transfer
```php
use OpsWay\YesBank\Api\Dto\BeneficiaryDto;

$beneficiary = new BeneficiaryDto;
$beneficiary->code = '<BENEFICIARY_CODE>';
$transfer = $api->fundTransfer()->startTransfer(
    '<UNIQUE_REQUEST_NUMBER>',
    '<ACCOUNT_NUMBER>',
    $beneficiary,
    '<AMOUNT>',
    '<TRANSFER_TYPE>',        // Optional, default 'ANY'
    '<CURRENCY_CODE>',        // Optional, default 'INR'
    '<PURPOSE_CODE>',         // Optional, default 'NODAL'
    '<REMITTER_INFO>',        // Optional, default 'FUND TRANSFER'
);

// Result: OpsWay\YesBank\Api\Dto\StartTransferResultDto Object
// (
//     [version] => 1
//     [requestReferenceNo] => <UNIQUE_REQUEST_NUMBER>
//     [uniqueResponseNo] => 2d96179c4b7e11ea8e950a0028fd0000
//     [attemptNo] => 1
//     [reqTransferType] => ANY
//     [statusCode] => AS
// )
```

### Get transfer status
```php
$status = $api->fundTransfer()->getTransferStatus('<UNIQUE_REQUEST_NUMBER>');

// Result: OpsWay\YesBank\Api\Dto\GetTransferStatusResultDto Object
// (
//     [version] => 2.0
//     [transferType] => ANY
//     [reqTransferType] => <TRANSFER_TYPE>
//     [transactionDate] => DateTime('2020-02-10 02:16:43.000000')
// 
//     [transferAmount] => <AMOUNT>
//     [transferCurrencyCode] => <CURRENCY_CODE>
//     [transactionStatus] => OpsWay\YesBank\Api\Dto\TransactionStatusDto Object
//         (
//             [statusCode] => FAILED
//             [subStatusCode] => ns:E6003
//             [bankReferenceNo] =>
//             [beneficiaryReferenceNo] =>
//         )
// )
```

## License
The MIT License (MIT). Please see [License File](./LICENSE.md) for more information.
