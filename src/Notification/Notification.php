<?php

namespace App\Notification;

use App\Model\Post;
use App\Controllers\SubscribeRepository;

/**
 * Class Notification
 * @package App\Notification
 */
class Notification
{
    private $notificationType;

    private $post;

    public function __construct(NotificationType $notificationType, Post $post)
    {
        $this->notificationType = $notificationType;
        $this->post = $post;
    }

    public function sendNotification()
    {
        foreach ((new SubscribeRepository())->getAllSubscribers() as $subscriber) {
            $this->notificationType->make($this->post, $subscriber);
        }
    }

}
