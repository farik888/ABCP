<?php

namespace NW\WebService\References\Operations\Notification;

use NW\WebService\References\Operations\Notification\Builders\TemplateDataDto;

class NotificationSender
{
    public function __construct(readonly TemplateDataDto $templateData, readonly MessagesClient $messagesClient)
    {
    }

    public function sendNotifications(): array
    {
        $result = $this->getDefaultResult();

        $employeeResult = $this->sendEmployeeNotifications();
        $clientResult = $this->sendClientNotifications();

        return array_merge($result, $employeeResult, $clientResult);
    }

    private function getDefaultResult(): array
    {
        return [
            'notificationEmployeeByEmail' => false,
            'notificationClientByEmail' => false,
            'notificationClientBySms' => [
                'isSent' => false,
                'message' => '',
            ],
        ];
    }

    private function sendEmployeeNotifications(): array
    {
        $result = $this->getDefaultResult();
        $resellerId = $this->templateData->creatorId;
        $emailFrom = getResellerEmailFrom($resellerId);
        $emails = getEmailsByPermit($resellerId, 'tsGoodsReturn');

        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $email) {
                $this->messagesClient->sendMessage([
                    [
                        'emailFrom' => $emailFrom,
                        'emailTo' => $email,
                        'subject' => __('complaintEmployeeEmailSubject', $this->templateData, $resellerId),
                        'message' => __('complaintEmployeeEmailBody', $this->templateData, $resellerId),
                    ],
                ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS);

                $result['notificationEmployeeByEmail'] = true;
            }
        }

        return $result;
    }

    private function sendClientNotifications(): array
    {
        $result = $this->getDefaultResult();
        $resellerId = $this->templateData->creatorId;
        $emailFrom = getResellerEmailFrom($resellerId);

        if (!empty($emailFrom) && !empty($this->templateData->clientName)) {
            $this->messagesClient->sendMessage([
                [
                    'emailFrom' => $emailFrom,
                    'emailTo' => $this->templateData->clientName,
                    'subject' => __('complaintClientEmailSubject', $this->templateData, $resellerId),
                    'message' => __('complaintClientEmailBody', $this->templateData, $resellerId),
                ],
            ], $resellerId, $this->templateData->clientId, NotificationEvents::CHANGE_RETURN_STATUS);

            $result['notificationClientByEmail'] = true;
        }

        if (!empty($this->templateData->clientName)) {
            $error = '';
            $isSent = $this->messagesClient->send($resellerId, $this->templateData->clientId,
                NotificationEvents::CHANGE_RETURN_STATUS, (int) $this->templateData->differences, $this->templateData,
                $error);

            $result['notificationClientBySms']['isSent'] = $isSent;
            $result['notificationClientBySms']['message'] = $error;
        }

        return $result;
    }
}
