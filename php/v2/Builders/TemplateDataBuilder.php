<?php

namespace NW\WebService\References\Operations\Notification\Builders;

use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\Employee;
use NW\WebService\References\Operations\Notification\Status;
use NW\WebService\References\Operations\Notification\TsReturnOperation;

class TemplateDataBuilder
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build(): TemplateDataDto
    {
        $client = $this->getClient();
        $creator = $this->getCreator();
        $expert = $this->getExpert();
        $differences = $this->getDifferences();

        return new TemplateDataDto(
            (int) $this->data['complaintId'],
            (string) $this->data['complaintNumber'],
            (int) $this->data['creatorId'],
            $creator->getFullName(),
            (int) $this->data['expertId'],
            $expert->getFullName(),
            (int) $this->data['clientId'],
            $client->getFullName() ?: $client->name,
            (int) $this->data['consumptionId'],
            (string) $this->data['consumptionNumber'],
            (string) $this->data['agreementNumber'],
            (string) $this->data['date'],
            $differences
        );
    }

    private function getClient(): Contractor
    {
        return Contractor::getById((int) $this->data['clientId']);
    }

    private function getCreator(): Contractor
    {
        return Employee::getById((int) $this->data['creatorId']);
    }

    private function getExpert(): Contractor
    {
        return Employee::getById((int) $this->data['expertId']);
    }

    private function getDifferences(): string
    {
        $notificationType = (int) $this->data['notificationType'];
        $resellerId = (int) $this->data['resellerId'];

        if ($notificationType === TsReturnOperation::NOTIFICATION_TYPE_NEW) {
            return __('NewPositionAdded', null, $resellerId);
        }

        if ($notificationType === TsReturnOperation::NOTIFICATION_TYPE_CHANGE && !empty($this->data['differences'])) {
            return __('PositionStatusHasChanged', [
                'FROM' => Status::getName((int) $this->data['differences']['from']),
                'TO' => Status::getName((int) $this->data['differences']['to']),
            ], $resellerId);
        }

        return '';
    }
}
