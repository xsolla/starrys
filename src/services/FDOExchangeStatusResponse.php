<?php

namespace Platron\Starrys\services;

use Billing\Entity\Kkt\KktChequeEntity;
use Carbon\Carbon;
use stdClass;

class FDOExchangeStatusResponse extends BaseServiceResponse
{
    /** @var int Длина очереди на отправку */
    public $SendQueueLen;

    /** @var int Номер первого неотправленного документа */
    public $DocumentNumber;

    /** @var int Дата первого неотправленного документа */
    public $Year;

    /** @var int */
    public $Month;

    /** @var int */
    public $Day;

    /** @var int */
    public $Hour;

    /** @var int */
    public $Minute;

    /** @var int  */
    public $Second;

    /**
     * @param stdClass $response
     */
    protected function importResponse(stdClass $response)
    {
        $this->import($response, 'Response', 'Status');
        $this->import($response, 'Response', 'Status', 'FirstFDDate', 'Date');
        $this->import($response, 'Response', 'Status', 'FirstFDDate', 'Time');
    }

    /**
     * @return int
     */
    public function getSendQueueLen()
    {
        return $this->SendQueueLen;
    }

    /**
     * @return int
     */
    public function getDocumentNumber()
    {
        return $this->DocumentNumber;
    }

    /**
     * @return string
     */
    public function getFirstFDDate()
    {
        if ($this->Hour != 0) {
            $fnExpirationDate = new Carbon();

            $fnExpirationDate->setDateTime(
                $this->Year + KktChequeEntity::CURRENT_MILLENNIUM,
                $this->Month,
                $this->Day,
                $this->Hour,
                $this->Minute,
                $this->Second
            );

            return $fnExpirationDate->format('Y-m-d H:i:s');
        }

        return '0';
    }

    /**
     * @return array
     */
    public function getLogInfo()
    {
        if (!$this->isValid()) {
            return [
                'FDOExchangeStatus' => 'Not available'
            ];
        }

        return [
            'FDOExchangeStatus' => [
                'FDOQueueLen' => $this->getSendQueueLen(),
                'LastSentDocumentNumber' => $this->getDocumentNumber(),
                'FirstDateNotSentDocument' => $this->getFirstFDDate()
            ]
        ];
    }
}