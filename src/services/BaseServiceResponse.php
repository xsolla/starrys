<?php

namespace Platron\Starrys\services;

use stdClass;

abstract class BaseServiceResponse {
    
    /** @var int */
    protected $errorCode;
	
	/** @var string */
	protected $errorMessages;
    
    /**
     * @param stdClass $response
     */
    public function __construct(stdClass $response) {
        foreach (get_object_vars($this) as $name => $value) {
			if (!empty($response->$name)) {
				$this->$name = $response->$name;
			}
		}
    }
    
    /**
     * Проверка на ошибки в ответе
     * @param array $response
     * @return boolean
     */
    public function isValid(){
        if(!empty($this->errorCode)){
            return false;
        }
        else {
            return true;
        }
    }
    
    /**
     * Получить код ошибки из ответа
     * @return int
     */
    public function getErrorCode(){
        return $this->errorCode;
    }
    
    /**
     * Получить описание ошибки
     * @return string
     */
    public function getErrorDescription(){
        return implode(',', $this->errorMessages);
    }
}
