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
    /**
     * @var NotificationType
     */
    private $notificationType;

    /**
     * @var Post
     */
    private $post;

    /**
     * Notification constructor.
     * @param NotificationType $notificationType
     * @param Post $post
     */
    public function __construct(NotificationType $notificationType, Post $post)
    {
        $this->notificationType = $notificationType;
        $this->post = $post;
    }

    /**
     * отправка уведомления подписчикам
     */
    public function sendNotification(): void
    {
        foreach ((new SubscribeRepository())->getAllSubscribers() as $subscriber) {
            $this->notificationType->make($this->post, $subscriber);
        }
    }

}
