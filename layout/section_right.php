<?php
/**
 * Правая секция сайта
 */

use App\Controllers\PublicControllers\PublicPostController;
use App\View;
/**
 * Блок подписки
 *
 * @var $token
 */


(new View('partials.subscribe',['token' => $token]))->render();

/**
 * Блок Последние посты
 */
(new View('partials.latest_posts', (new PublicPostController())->latestPosts()))->render();