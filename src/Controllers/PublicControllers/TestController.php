<?php
/**
 * Created by PhpStorm.
 * User: diffe
 * Date: 05.11.2020
 * Time: 18:54
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Controllers\SubscribeRepository;


class TestController extends Controller
{
    public string $name;

    public function test()
    {
        $subscriber = (new SubscribeRepository())->getSubscriber('rrrrlll@mail.ru');

        dump($subscriber);
        echo '<a href="' . SITE_ROOT . '/manage-subscribes/unsubscribe-by-link?email=' . $subscriber->email .'&code=' .
            $subscriber->hash . '" >' . SITE_ROOT . '/manage-subscribes/unsubscribe-by-link?email=' . $subscriber->email .'&code=' .
            $subscriber->hash . '</a>';
    }
}