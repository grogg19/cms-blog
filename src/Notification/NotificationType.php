<?php

namespace App\Notification;

use App\Model\Subscriber;
use App\Model\Post;

/**
 * Interface NotificationType
 * @package App\Notification
 */
interface NotificationType
{
    public function make(Post $post, Subscriber $subscriber);
}
