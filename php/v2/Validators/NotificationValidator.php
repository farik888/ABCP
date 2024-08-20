<?php

namespace NW\WebService\References\Operations\Notification\Validators;

use NW\WebService\References\Operations\Notification\Contractor;
use NW\WebService\References\Operations\Notification\Exceptions\NotificationValidatorException;

class NotificationValidator
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @throws NotificationValidatorException
     */
    public function validate(): void
    {
        $resellerId = (int) ($this->data['resellerId'] ?? 0);
        $notificationType = (int) ($this->data['notificationType'] ?? 0);

        if (empty($resellerId)) {
            throw new NotificationValidatorException('Empty resellerId', 400);
        }

        if (empty($notificationType)) {
            throw new NotificationValidatorException('Empty notificationType', 400);
        }

        $client = Contractor::getById((int) $this->data['clientId']);
        if ($client->type !== Contractor::TYPE_CUSTOMER || $client->Seller->id !== $resellerId) {
            throw new NotificationValidatorException('Client not found!', 400);
        }

    }
}
