<?php

namespace Platron\Starrys\services;

use Platron\Starrys\data_objects\Line;
use Platron\Starrys\SdkException;

class ComplexRequest extends BaseServiceRequest{
    
	/** @var string */
	protected $device = 'auto';
	/** @var string */
	protected $fullResponse = false;
	/** @var string */
	protected $group;
	/** @var int */
	protected $requestId;
	/** @var int */
	protected $documentType;
	/** @var int */
	protected $taxMode;
	/** @var int */
	protected $phone;
	/** @var string */
	protected $email;
	/** @var string */
	protected $place;
	/** @var Line[] */
	protected $lines;
	/** @var string */
	protected $password;
	/** @var float */
	protected $cash;
	/** @var float[] */
	protected $nonCash;
	/** @var float */
	protected $advancePayment;
	/** @var float */
	protected $credit;
	/** @var float */
	protected $consideration;
	
    const 
        DOCUMENT_TYPE_SELL = 0, // Приход
        DOCUMENT_TYPE_SELL_REFUND = 2, // Возврат прихода
        DOCUMENT_TYPE_BUY = 1, // Расход
        DOCUMENT_TYPE_BUY_REFUND = 3; // Возврат расхода
    
    const 
        TAX_MODE_OSN = 0, // общая СН
        TAX_MODE_USN_INCOME = 1, // упрощенная СН (доходы)
        TAX_MODE_USN_INCOME_OUTCOME = 2, // упрощенная СН (доходы минус расходы)
        TAX_MODE_ENDV = 3, // единый налог на вмененный доход
        TAX_MODE_ESN = 4, // единый сельскохозяйственный налог
        TAX_MODE_PATENT = 5; // патентная СН
    
    /**
     * @inheritdoc
     */
    public function getUrlPath() {
        return '/fr/api/v2/Complex';
    }
        
	/**
	 * @param int $requestId id запроса
	 */
	public function __construct($requestId) {
		$this->requestId = $requestId;
	}
	
	/**
	 * Установить идентификатор предприятия. Передается в случае использования одного сертификата на несколько предприятий
	 * @param string $group
	 * return $this
	 */
	public function addGroup($group){
		$this->group = $group;
		return $this;
	}
	
	/**
	 * Установить тип чека
	 * @param string $documentType
	 * @return $this
	 */
	public function addDocumentType($documentType){
		if(!in_array($documentType, $this->getDocumentTypes())){
            throw new SdkException('Wrong payment type');
        }
        
        $this->documentType = $documentType;
        return $this;
	}

	/**
	 * Установить режим налогообложения. Нужно если у организации существует более 1 системы налогообложения
	 * @param int $taxMode
	 * @return $this
	 */
	public function addTaxMode($taxMode){
		if(!in_array($taxMode, $this->getTaxModes())){
            throw new SdkException('Wrong tax mode');
        }
		
		$this->taxMode = $taxMode;
		return $this;
	}
	
	/**
	 * Установить телефон покупателя
	 * @param int $phone
	 * @return $this
	 */
	public function addPhone($phone){
		$this->phone = $phone;
		return $this;
	}
	
	/**
	 * Установить email покупателя
	 * @param string $email
	 * @return $this
	 */
	public function addEmail($email){
		$this->email = $email;
		return $this;
	}
	
	/**
	 * Сумма оплаты наличными. Если сумма равна нулю, то это поле можно опустить
	 * @param float $cash
	 * @return $this
	 */
	public function addCash($cash){
		$this->cash = $cash;
		return $this;
	}
	
	/**
	 * Массив из 3-ех элеметов с суммами оплат 3 различных типов. Обычно передается только первое значение
	 * @param type $firstAmount
	 * @param type $secondAmount
	 * @param type $thirdAmount
	 * @return $this
	 */
	public function addNonCash($firstAmount, $secondAmount = 0, $thirdAmount = 0){
		$this->nonCash = [$firstAmount, $secondAmount, $thirdAmount];
		return $this;
	}
	
	/**
	 * Сумма оплаты предоплатой. Поле не обязательное
	 * @param float $advancePayment
	 * @return $this
	 */
	public function addAdvancePayment($advancePayment){
		$this->advancePayment = $advancePayment;
		return $this;
	}
	
	/**
	 * Сумма оплаты постоплатой. Не обязательное
	 * @param float $credit
	 * @return $this
	 */
	public function addCredit($credit){
		$this->credit = $credit;
		return $this;
	}
	
	/**
	 * Сумма оплаты встречным предоставлением. Не обязательное
	 * @param float $consideration
	 * @return $this
	 */
	public function addConsideration($consideration){
		$this->consideration = $consideration;
		return $this;
	}
	
	/**
	 * Место расчетов. Можно указать адрес сайта
	 * @param string $place
	 * @return $this
	 */
	public function addPlace($place){
		$this->place = $place;
		return $this;
	}
	
	/**
	 * Добавить позицию в чек
	 * @param Line $line
	 * @return $this
	 */
	public function addLine(Line $line){
		$this->lines[] = $line;
		return $this;
	}
	
	/**
	 * Установить пароль. Не обязательно. Подробнее смотри в полной версии документации
	 * @param int $password
	 * @return $this
	 */
	public function addPassword($password){
		$this->password = $password;
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
    public function getParameters() {
        $lines = [];
        foreach($this->lines as $line){
            $lines[] = $line->getParameters();
        }
		
        $params = [
			'Device' => $this->device,
			'Password' => $this->password,
            'RequestId' => (string)$this->requestId,
            'Lines' => $lines,
			'Cash' => $this->cash,
			'NonCash' => $this->nonCash,
			'AdvancePayment' => $this->advancePayment,
			'Credit' => $this->credit,
			'Consideration' => $this->consideration,
			'TaxMode' => $this->taxMode,
			'PhoneOrEmail' => $this->email ? $this->email : $this->phone,
			'Place' => $this->place,
			'FullResponse' => $this->fullResponse,
        ];
        
        return $params;
    }
    
    protected function getDocumentTypes(){
        return [
            self::DOCUMENT_TYPE_BUY,
            self::DOCUMENT_TYPE_BUY_REFUND,
            self::DOCUMENT_TYPE_SELL,
            self::DOCUMENT_TYPE_SELL_REFUND,
        ];
    }

    protected function getTaxModes(){
        return [
            self::TAX_MODE_ENDV,
            self::TAX_MODE_ESN,
            self::TAX_MODE_OSN,
            self::TAX_MODE_PATENT,
            self::TAX_MODE_USN_INCOME,
            self::TAX_MODE_USN_INCOME_OUTCOME,
        ];
    }
}
