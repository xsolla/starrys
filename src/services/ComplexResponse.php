<?php

namespace Platron\Starrys\services;

use Platron\Starrys\services\BaseServiceResponse;
use stdClass;

class ComplexResponse extends BaseServiceResponse {

    /** @var float Сдача */
    public $Change;
	/** @var string Дата и время формирования документа */
    public $Date;
	/** @var string Регистрационный номер документа */
    public $DeviceRegistrationNumber;
	/** @var string Заводской номер устройста */
    public $DeviceSerialNumber;
	/** @var string Номер фискального накопителя, в котором сформирован документ */
	public $FNSerialNumber;
    /** @var string Номер фискального документа */
	public $FiscalDocNumber;
	/** @var string Фискальный признак документа */
	public $FiscalSign;
	/** @var float Итог чека */
	public $GrandTotal;
	/** @var string QR-код чека */
	public $QR;
	
	/** @var string Заводской номер устройства */
	public $Name;
	/** @var string Адрес устройства */
	public $Address;
	
    /**
     * @inheritdoc
     */    
    public function __construct(stdClass $response) {
		
		if(!empty($response->FCEError)){
			$this->errorCode = $response->FCEError;
			$this->errorMessages[] = $response->ErrorDescription;
			return $this;
		}
		
        if(!empty($response->Response->Error)){
			$this->errorCode = $response->Response->Error;
			foreach($response->Response->ErrorMessages as $message){
				$this->errorMessages .= $message;
			}
			return $this;
		}
		
		parent::__construct($response);
		parent::__construct($response->Device);
    }
}
