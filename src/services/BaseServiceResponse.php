<?php

namespace Platron\Starrys\services;

use Platron\Starrys\InsufficientResponseException;
use stdClass;

abstract class BaseServiceResponse
{

    /** @var int */
    protected $errorCode;

    /** @var string */
    protected $errorMessages;

    /**
     * @param stdClass $response
     * @param array $subFields
     * @throws \Platron\Starrys\InsufficientResponseException
     */
    public function import(stdClass $response, ...$subFields)
    {
        $subResponse = $this->getSubResponse($response, ...$subFields);

        foreach (get_object_vars($this) as $name => $value) {
            if (!empty($subResponse->$name)) {
                $this->$name = $subResponse->$name;
            }
        }
    }

    /**
     * @param stdClass $response
     * @param array $subFields
     * @return stdClass|array
     * @throws \Platron\Starrys\InsufficientResponseException
     */
    public function getSubResponse(stdClass $response, ...$subFields)
    {
        $subResponse = $response;
        foreach ($subFields as $field) {
            if (property_exists($subResponse, $field)) {
                $subResponse = $subResponse->$field;
            } else {
                throw new InsufficientResponseException($response, $field, $subFields);
            }
        }
        return $subResponse;
    }

    public function retrieveByTagId(array $tags, $tagId)
    {
        /** @var stdClass $tag */
        foreach ($tags as $tag) {
            if (isset($tag->TagID, $tag->Value) && $tagId === $tag->TagID) {
                return $tag->Value;
            }
        }

        return null;
    }

    /**
     * Проверка на ошибки в ответе
     * @return boolean
     */
    public function isValid()
    {
        return empty($this->errorCode);
    }

    /**
     * Возвращает вложенный ответ на команду Complex по имени
     * @param array $complex
     * @param string $commandName
     * @return stdClass
     * @throws \Platron\Starrys\InsufficientResponseException
     */
    public function findCommandInComplexByName(array $complex, $commandName)
    {
        foreach ($complex as $command) {
            if (false !== strpos($command->Path, $commandName)) {
                return $command;
            }
        }

        throw new InsufficientResponseException($complex, $commandName);
    }

    /**
     * Получить код ошибки из ответа
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Получить описание ошибки
     * @return string
     */
    public function getErrorDescription()
    {
        return implode(',', $this->errorMessages);
    }
}
