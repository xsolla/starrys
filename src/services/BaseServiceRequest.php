<?php

namespace Platron\Starrys\services;

abstract class BaseServiceRequest
{

    const REQUEST_URL = 'https://fce.starrys.ru:4443/fr/api/v2/';

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
        $filledvars = array();
        foreach (get_object_vars($this) as $name => $value) {
            if ($value) {
                $filledvars[$name] = (string)$value;
            }
        }

        return $filledvars;
    }
}
