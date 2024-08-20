<?php

namespace NW\WebService\References\Operations\Notification;

use NW\WebService\References\Operations\Notification\Builders\TemplateDataBuilder;
use NW\WebService\References\Operations\Notification\Validators\NotificationValidator;

class TsReturnOperation extends ReferencesOperation
{
    public const NOTIFICATION_TYPE_NEW = 1;
    public const NOTIFICATION_TYPE_CHANGE = 2;


    public function doOperation(): array
    {
        $data = (array) $this->getRequest('data');
        $validator = new NotificationValidator($data);
        $validator->validate();

        $templateDataBuilder = new TemplateDataBuilder($data);
        $templateData = $templateDataBuilder->build();

        return (new NotificationSender($templateData, new MessagesClient()))->sendNotifications();
    }

}
