<?php

namespace Platron\Starrys\services;

abstract class BaseServiceRequest
{

    const REQUEST_URL = 'https://fce.starrys.ru:4443/fr/api/v2/';

    /** @var int */
    protected $requestId;

    /** @var int */
    protected $password;

    /**
     * @param int $requestId id запроса
     */
    public function __construct($requestId)
    {
        $this->requestId = $requestId;
    }

    /**
     * Получить путь для запроса относительно домена
     * @return string
     */
    abstract public function getUrlPath();

    /**
     * Получить параметры, сгенерированные командой
     * @return array
     */
    public function getParameters()
    {
        $parameters = array();

        foreach (get_object_vars($this) as $name => $value) {
            if ($value) {
                $parameters[ucfirst($name)] = $value;
            }
        }

        return $parameters;
    }

    /**
     * Установить пароль. Обязательно. Подробнее смотри в полной версии документации
     * @param int $password
     * @return $this
     */
    public function addPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
