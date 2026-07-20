<?php

namespace App\Interfaces;

interface NotificationServiceInterface
{

    /**
     * إرسال إشعار للمريض
     *
     * @param string $recipient (رقم الهاتف أو الإيميل)
     * @param string $message (نص الرسالة)
     * @return bool
     */
    public function sendNotification(string $recipient, string $message);
}
