<?php

namespace App\Services\Notification;

class EmailNotificationStrategy implements NotificationInterface
{

    public function send(string $title)
    {
        echo "Calling external email service ..." . PHP_EOL;
        echo "Sending email ..." . PHP_EOL;
    }

}