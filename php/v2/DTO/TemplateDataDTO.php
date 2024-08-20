<?php

namespace NW\WebService\References\Operations\Notification\Builders;

class TemplateDataDto
{
    public int $complaintId;
    public string $complaintNumber;
    public int $creatorId;
    public string $creatorName;
    public int $expertId;
    public string $expertName;
    public int $clientId;
    public string $clientName;
    public int $consumptionId;
    public string $consumptionNumber;
    public string $agreementNumber;
    public string $date;
    public string $differences;

    public function __construct(
        int $complaintId,
        string $complaintNumber,
        int $creatorId,
        string $creatorName,
        int $expertId,
        string $expertName,
        int $clientId,
        string $clientName,
        int $consumptionId,
        string $consumptionNumber,
        string $agreementNumber,
        string $date,
        string $differences
    ) {
        $this->complaintId = $complaintId;
        $this->complaintNumber = $complaintNumber;
        $this->creatorId = $creatorId;
        $this->creatorName = $creatorName;
        $this->expertId = $expertId;
        $this->expertName = $expertName;
        $this->clientId = $clientId;
        $this->clientName = $clientName;
        $this->consumptionId = $consumptionId;
        $this->consumptionNumber = $consumptionNumber;
        $this->agreementNumber = $agreementNumber;
        $this->date = $date;
        $this->differences = $differences;
    }
}
