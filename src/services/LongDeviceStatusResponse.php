<?php

namespace Platron\Starrys\services;

use Billing\Entity\Kkt\KktChequeEntity;
use Carbon\Carbon;
use stdClass;

class LongDeviceStatusResponse extends BaseServiceResponse
{
    /** @var int Код состояния устройства */
    public $Mode;

    /** @var int Фаза жизни ФН */
    public $FNFlags;

    /** @var int Дата окончания действия ФН */
    public $Year;

    /** var int */
    public $Month;

    /** @var int */
    public $Day;

    /**
     * @param stdClass $response
     */
    protected function importResponse(stdClass $response)
    {
        $this->import($response, 'Response', 'Status');
        $this->import($response, 'Response', 'Status', 'FNExpirationDate');
    }

    /**
     * @return string
     */
    public function getModeStatus()
    {
        switch ($this->Mode) {
            case 0:
            case 2:
            case 3:
            case 4:
            case 6:
            case 8:
            case 24:
            case 40:
            case 56:
                return 'Ok';
            default:
                return 'Fatal error';
        }
    }

    /**
     * @return string
     */
    public function getFNFlagsStatus()
    {
        switch ($this->FNFlags) {
            case 0:
                return 'Ok';
            case 1:
                return 'Urgent replacement СС is required (less than 3 days left)';
            case 2:
                return 'Replacement СС is required (less than 30 days left)';
            case 4:
                return 'Memory overflow (less than 10% left)';
            case 8:
                return 'OFD timeout';
            default:
                return 'Fatal error';
        }
    }

    /**
     * @return string
     */
    public function getExpirationDateStatus()
    {
        $fnExpirationDate = new Carbon();

        $fnExpirationDate->setDateTime(
            $this->Year + KktChequeEntity::CURRENT_MILLENNIUM,
            $this->Month,
            $this->Day,
            0,
            0
        );

        return $fnExpirationDate->format('Y-m-d');
    }

    /**
     * @return array
     */
    public function getLogInfo()
    {
        if (!$this->isValid()) {
            return [
                'longDeviceStatus' => 'Not available'
            ];
        }

        return [
            'longDeviceStatus' => [
                'deviceCodeStatus' => $this->getModeStatus(),
                'fnFlagsStatus' => $this->getFNFlagsStatus(),
                'expirationDateStatus' => $this->getExpirationDateStatus()
            ]
        ];
    }
}
