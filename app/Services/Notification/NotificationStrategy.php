<?php

namespace App\Services\Notification;

class NotificationStrategy implements NotificationInterface
{

    public function __construct(NotificationInterface $sender)
    {
        $this->sender = $sender;
    }

    public function send(string $title)
    {
        return $this->sender->send($title);
    }

}