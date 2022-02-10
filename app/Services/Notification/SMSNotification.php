<?php

namespace App\Services\Notification;

class SMSNotification implements NotificationInterface
{

    public function send(string $title)
    {
        echo "Calling external sms service ..." . PHP_EOL;
        echo "Sending sms ..." . PHP_EOL;
    }

}