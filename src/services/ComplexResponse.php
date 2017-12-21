<?php

namespace Platron\Starrys\services;

use Platron\Starrys\InsufficientResponseException;
use stdClass;

class ComplexResponse extends BaseServiceResponse
{
    const TURN_NUMBER_TAG_ID = 1038;
    const CLOSE_DOCUMENT_COMMAND_NAME = 'CloseDocument';

    /** @var int День формирования документа */
    public $Day;
    /** @var int Месяц формирования документа */
    public $Month;
    /** @var int Год формирования документа */
    public $Year;
    /** @var int Час формирования документа */
    public $Hour;
    /** @var int Минута формирования документа */
    public $Minute;
    /** @var int Секунда формирования документа */
    public $Second;
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
    /** @var string Номер смены */
    public $TurnNumber;

    /** @var string Заводской номер устройства */
    public $Name;
    /** @var string Адрес устройства */
    public $Address;

    /**
     * @inheritdoc
     */
    public function __construct(stdClass $response)
    {
        if (!empty($response->FCEError)) {
            $this->errorCode = $response->FCEError;
            $this->errorMessages[] = $response->ErrorDescription;
            return;
        }

        if (!empty($response->Response->Error)) {
            $this->errorCode = $response->Response->Error;
            foreach ($response->Response->ErrorMessages as $message) {
                $this->errorMessages .= $message;
            }
            return;
        }

        $this->import($response);
        $this->import($response, 'Date', 'Date');
        $this->import($response, 'Date', 'Time');
        $this->importTurnNumber($response);
    }

    /**
     * @param stdClass $response
     * @throws \Platron\Starrys\InsufficientResponseException
     */
    private function importTurnNumber(stdClass $response)
    {
        $closeDocumentCommand = $this->findCommandInComplexByName(
            $response->Responses,
            self::CLOSE_DOCUMENT_COMMAND_NAME
        );

        if (isset($closeDocumentCommand->Response->TurnNumber)) {
            $this->TurnNumber = $closeDocumentCommand->Response->TurnNumber;
        } else {
            $fiscalDocument = $this->getSubResponse($closeDocumentCommand, 'Response', 'FiscalDocument', 'Value');
            $this->TurnNumber = $this->retrieveByTagId($fiscalDocument, self::TURN_NUMBER_TAG_ID);
        }

        if (empty($this->TurnNumber)) {
            throw new InsufficientResponseException($closeDocumentCommand, 'TurnNumber');
        }
    }
}
