Platron Starrys SDK
===============
## Установка

Проект предполагает через установку с использованием composer
<pre><code>composer require platron/starrys</pre></code>

## Тесты
Для работы тестов необходим PHPUnit, для установки необходимо выполнить команду
```
composer install
```
Для того, чтобы запустить интеграционные тесты нужно скопировать файл tests/integration/MerchantSettingsSample.php удалив 
из названия Sample и вставив настройки магазина. Так же в папку tests/integration/merchant_data необходимо положить приватный
ключ и сертификат. После выполнить команду из корня проекта
```
vendor/bin/phpunit tests/integration
```

## Примеры использования

### 1. Создание чека

```php
$client = new Platron\Starrys\clients\PostClient();

$receiptPosition = new Platron\Starrys\data_objects\ReceiptPosition('test product', 100.00, 2, Platron\Starrys\data_objects\ReceiptPosition::TAX_VAT10);

$createDocumentService = (new Platron\Starrys\services\CreateDocumentRequest($transactionId))
    ->addCustomerEmail('test@test.ru')
    ->addCustomerPhone('79268752662')
    ->addGroupCode('groupCode')
    ->addInn('inn')
    ->addOperationType(Platron\Starrys\services\CreateDocumentRequest::OPERATION_TYPE_BUY)
    ->addPaymentType(Platron\Starrys\services\CreateDocumentRequest::PAYMENT_TYPE_ELECTRON)
    ->addTaxatitionSystem(Platron\Starrys\services\CreateDocumentRequest::TAXATITION_SYSTEM_ESN)
    ->addReceiptPosition($receiptPosition);
$createDocumentResponse = new Platron\Starrys\services\CreateDocumentResponse($client->sendRequest($createDocumentService));
```