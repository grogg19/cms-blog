<?php
/**
 * Правая секция сайта
 */

use App\View;
/**
 * Блок подписки
 */
(new View('partials/subscribe'))->render();

/**
 * Блок Последние посты
 */
(new View('partials/latest_posts', (new \App\Controllers\PublicControllers\PublicPostController())->latestPosts()))->render();