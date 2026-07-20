<?php

namespace App\Services;

use App\Interfaces\NotificationServiceInterface;

class WhatsAppNotificationService implements NotificationServiceInterface
{
    public function sendNotification(string $recipient, string $message): bool
    {
        return true;
    }
}
