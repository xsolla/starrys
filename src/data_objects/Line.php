<?php

namespace Platron\Starrys\data_objects;

use Platron\Starrys\CurlException;

class Line extends BaseDataObject
{

    const
        TAX_NONE = 4, // Не облагается
        TAX_VAT0 = 3, // 0%
        TAX_VAT10 = 2, // 10%
        TAX_VAT18 = 1, // 18%
        TAX_VAT110 = 6, // Ставка 10/110
        TAX_VAT118 = 5; // Ставка 18/118

    const
        PAY_ATTRIBUTE_TYPE_FULL_PRE_PAID_BEFORE_GET_PRODUCT = 1, // Полная предварительная оплата до передачи товара
        PAY_ATTRIBUTE_TYPE_PARTIAL_PRE_PAID_BEFORE_GET_PRODUCT = 2, // Частичная предварительная оплата до передачи товара
        PAY_ATTRIBUTE_TYPE_PRE_PAID = 3, // Аванс
        PAY_ATTRIBUTE_TYPE_FULL_PAID_WITH_GET_PRODUCT = 4, // Полная оплата в том числе с учетом аванса в момент передачи товара
        PAY_ATTRIBUTE_TYPE_PRE_PAID_WITH_GET_PRODUCT_AND_CREDIT = 5, // Частичная оплата с передачей товара и оформлением кредита
        PAY_ATTRIBUTE_TYPE_NOT_PAID_WITH_GET_PRODUCT_AND_CREDIT = 6, // Передача товара без оплаты и оформление кредита
        PAY_ATTRIBUTE_TYPE_PAID_CREDIT = 7; // Оплата кредита. Если это значение присутствует - в чеке может быть только 1 позиция

    /** @var int */
    protected $Qty;
    /** @var float */
    protected $Price;
    /** @var int */
    protected $payAttribute;
    /** @var int */
    protected $taxId;
    /** @var string */
    protected $description;

    /**
     * @param string $description Наименование товарной позиции
     * @param float $qty Количество. Указывается в штуках. До 3 знаков после запятой
     * @param int $price Цена указывается в копейках
     * @param int $taxId Налоговая ставка из констант
     * @throws CurlException
     */
    public function __construct($description, $qty, $price, $taxId)
    {
        if (!in_array($taxId, $this->getTaxes())) {
            throw new \InvalidArgumentException('Wrong tax');
        }

        $this->Qty = (int)($qty * 1000);
        $this->Price = (float)$price;
        $this->taxId = $taxId;
        $this->description = $description;
    }

    /**
     * Признак способа расчета. Задается из констант. Не обязателен при БСО
     * @param int $payAttribute
     */
    public function addPayAttribute($payAttribute)
    {
        if (!in_array($payAttribute, $this->getPayAttributes())) {
            throw new \InvalidArgumentException('Wrong pay attribute');
        }

        $this->payAttribute = $payAttribute;
    }

    /**
     * Получить все возможные налоговые ставки
     */
    protected function getTaxes()
    {
        return [
            self::TAX_NONE,
            self::TAX_VAT0,
            self::TAX_VAT10,
            self::TAX_VAT110,
            self::TAX_VAT118,
            self::TAX_VAT18,
        ];
    }

    /**
     * Получить все возможные налоговые ставки
     */
    protected function getPayAttributes()
    {
        return [
            self::PAY_ATTRIBUTE_TYPE_FULL_PAID_WITH_GET_PRODUCT,
            self::PAY_ATTRIBUTE_TYPE_FULL_PRE_PAID_BEFORE_GET_PRODUCT,
            self::PAY_ATTRIBUTE_TYPE_NOT_PAID_WITH_GET_PRODUCT_AND_CREDIT,
            self::PAY_ATTRIBUTE_TYPE_PAID_CREDIT,
            self::PAY_ATTRIBUTE_TYPE_PARTIAL_PRE_PAID_BEFORE_GET_PRODUCT,
            self::PAY_ATTRIBUTE_TYPE_PRE_PAID,
            self::PAY_ATTRIBUTE_TYPE_PRE_PAID_WITH_GET_PRODUCT_AND_CREDIT,
        ];
    }
}
