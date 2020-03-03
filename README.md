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
$config->setBasicAuthLogin('<BASIC_AUTH_LOGIN>');          // Optional
$config->setBasicAuthPassword('<BASIC_AUTH_PASSWORD>');    // Optional

$transport = new Transport\HttpTransport($config);
$api = new Api($transport);
```

### Fund Transfer
#### Get Balance
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

#### Start Transfer
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

#### Get transfer status
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

### Maintain Beneficiary
Some code preparations:
```php
// Prepare bank and beneficiary 
use OpsWay\YesBank\Api\Dto\BankDto;
use OpsWay\YesBank\Api\Dto\BeneficiaryDto;

$beneficiary = new BeneficiaryDto;
$beneficiary->code = '<BENEFICIARY_CODE>';
$beneficiary->name = '<BENEFICIARY_NAME>';
$beneficiary->type = '<BENEFICIARY_TYPE>';
$beneficiary->accountNo = '<BENEFICIARY_ACCOUNT_NO>';

$bank = new BankDto();
$bank->name = '<BANK_NAME>';
$bank->ifscCode = '<BANK_IFSC_CODE>';

// You can change some transport config params (optional)
$api->getTransport()->getConfig()->setBasicAuthLogin('<BASIC_AUTH_LOGIN>');
$api->getTransport()->getConfig()->setBasicAuthPassword('<BASIC_AUTH_PASSWORD>');
```

#### Add Beneficiary
```php
$result = $api->maintainBeneficiary()->add('<ACCOUNT_NUMBER>', $beneficiary, $bank, '<AMOUNT>', '<CURRENCY_CODE>', '<PAYMENT_TYPE>');

// Result: OpsWay\YesBank\Api\Dto\MaintainBeneficiaryResponseDto Object
// (
//    [requestStatus] => SUCCESS
//    [custId] => <CUSTOMER_ID>
//    [srcAccountNo] => <ACCOUNT_NUMBER>
//    [beneficiaryCd] => <BENEFICIARY_CODE>
//    [beneName] => <BENEFICIARY_NAME>
//    [beneType] => <BENEFICIARY_TYPE>
//    [beneAccountNo] => <BENEFICIARY_ACCOUNT_NO>
//    [bankName] => <BANK_NAME>
//    [ifscCode] => <BANK_IFSC_CODE>
//    [transactionLimit] => <AMOUNT>
//    [currencyCd] => <CURRENCY_CODE>
//    [action] => ADD
//    [error] => stdClass Object()
// )
```

#### Modify Beneficiary
```php
$result = $api->maintainBeneficiary()->modify('<ACCOUNT_NUMBER>', $beneficiary, $bank, '<AMOUNT>', '<CURRENCY_CODE>', '<PAYMENT_TYPE>');

// Result: OpsWay\YesBank\Api\Dto\MaintainBeneficiaryResponseDto Object
// (
//    [requestStatus] => SUCCESS
//    [reqRefNo] => <REQUEST_REFERENCE_NO>
//    [custId] => <CUSTOMER_ID>
//    [srcAccountNo] => <ACCOUNT_NUMBER>
//    [beneficiaryCd] => <BENEFICIARY_CODE>
//    [beneName] => <BENEFICIARY_NAME>
//    [beneType] => <BENEFICIARY_TYPE>
//    [beneAccountNo] => <BENEFICIARY_ACCOUNT_NO>
//    [bankName] => <BANK_NAME>
//    [ifscCode] => <BANK_IFSC_CODE>
//    [transactionLimit] => <AMOUNT>
//    [currencyCd] => <CURRENCY_CODE>
//    [action] => MODIFY
//    [error] => stdClass Object()
// )
```

#### Verify Beneficiary
```php
$result = $api->maintainBeneficiary()->verify('<ACCOUNT_NUMBER>', '<BENEFICIARY_CODE>', '<PAYMENT_TYPE>');

// Result: OpsWay\YesBank\Api\Dto\MaintainBeneficiaryResponseDto Object
// (
//    [requestStatus] => SUCCESS
//    [reqRefNo] => <REQUEST_REFERENCE_NO>
//    [custId] => <CUSTOMER_ID>
//    [srcAccountNo] => <ACCOUNT_NUMBER>
//    [beneficiaryCd] => <BENEFICIARY_CODE>
//    [beneName] => <BENEFICIARY_NAME>
//    [beneType] => <BENEFICIARY_TYPE>
//    [beneAccountNo] => <BENEFICIARY_ACCOUNT_NO>
//    [bankName] => <BANK_NAME>
//    [ifscCode] => <BANK_IFSC_CODE>
//    [transactionLimit] => <AMOUNT>
//    [currencyCd] => <CURRENCY_CODE>
//    [action] => Verify
//    [error] => stdClass Object()
// )
```

#### Disable Beneficiary
Note: In order to enable a disabled beneficiary, perform a `modify` operation on the beneficiary.

```php
$result = $api->maintainBeneficiary()->disable('<ACCOUNT_NUMBER>', '<BENEFICIARY_CODE>', '<PAYMENT_TYPE>');

// Result: OpsWay\YesBank\Api\Dto\MaintainBeneficiaryResponseDto Object
// (
//    [requestStatus] => SUCCESS
//    [reqRefNo] => <REQUEST_REFERENCE_NO>
//    [custId] => <CUSTOMER_ID>
//    [srcAccountNo] => <ACCOUNT_NUMBER>
//    [beneficiaryCd] => <BENEFICIARY_CODE>
//    [action] => DISABLE
//    [error] => stdClass Object()
// )
```

## License
The MIT License (MIT). Please see [License File](./LICENSE.md) for more information.
