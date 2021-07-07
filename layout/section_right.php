<?php
/**
 * Правая секция сайта
 */

/**
 * Блок подписки
 *
 * @var $token
 * @var $user
 */

require (APP_DIR . DIRECTORY_SEPARATOR . 'layout/partials/subscribe.php');
/**
 * Блок Последние посты
 */
((new \App\Controllers\PublicControllers\PublicPostController())->latestPosts())->render();
