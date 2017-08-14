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
$client = new PostClient($this->starrysApiUrl, $this->secretKeyPath, $this->certPath);
$line = new Line('Test product', 1, 10.00, Line::TAX_VAT18);
$line->addPayAttribute(Line::PAY_ATTRIBUTE_TYPE_FULL_PAID_WITH_GET_PRODUCT);

$complexServise = new ComplexRequest(time());
$complexServise->addDocumentType(ComplexRequest::DOCUMENT_TYPE_BUY)
        ->addEmail('test@test.ru')
        ->addGroup($this->group)
        ->addPhone('79050000000')
        ->addPlace('www.test.ru')
        ->addTaxMode($this->taxMode)
        ->addLine($line)
        ->addNonCash(10.00);

$response = new ComplexResponse($client->sendRequest($complexServise));
```