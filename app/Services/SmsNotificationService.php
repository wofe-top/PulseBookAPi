<?php

namespace App\Services;


use App\Interfaces\NotificationServiceInterface;

class SmsNotificationService implements NotificationServiceInterface
{
    public function sendNotification(string $recipient, string $message): bool
    {

        return true;
    }
}
