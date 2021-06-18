<?php
/**
 * Правая секция сайта
 */

use App\Controllers\PublicControllers\PublicPostController;
use App\View;
use App\Repository\UserRepository;
use App\Cookie\Cookie;
use function Helpers\session;
/**
 * Блок подписки
 *
 * @var $token
 */
$user = (!empty(Cookie::get('authUser')) && !empty(Cookie::get('PHPSESSID'))) ?
    (new UserRepository())->getUserById(session()->get('userId')) : null;

(new View('partials.subscribe',[
    'token' => $token,
    'user' => $user
]))->render();

/**
 * Блок Последние посты
 */
(new PublicPostController())->latestPosts()->render();