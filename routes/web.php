<?php

use App\Controllers\BackendControllers\AdminAccountController;
use App\Controllers\BackendControllers\AdminCommentController;
use App\Controllers\BackendControllers\AdminPostController;
use App\Controllers\BackendControllers\AdminSettingsController;
use App\Controllers\BackendControllers\AdminSubscribeController;
use App\Controllers\BackendControllers\AdminUserManagerController;
use App\Controllers\PublicControllers\LoginController;
use App\Controllers\PublicControllers\RegisterController;
use App\Controllers\PublicControllers\PublicCommentController;
use App\Controllers\PublicControllers\PublicPostController;
use App\Controllers\PublicControllers\PublicSubscribeController;
use App\Controllers\PublicControllers\StaticPagesController;
use App\Controllers\BackendControllers\AdminStaticPagesController;
use App\Migrations\MigrationLoader;
use App\Toasts\Toast;
use App\Exception\NotFoundException;
use App\Repository\StaticPagesRepository;
use App\Router;

$router = new Router(); // создаем маршрутизатор

/** Главная страница с лентой постов  */
$router->get('get','/installdb', MigrationLoader::class . '@makeMigrations');

/** Главная страница с лентой постов  */
$router->get('get','/', PublicPostController::class . '@allPosts');

/** Добавочная загрузка постов в ленте  */
$router->get('post','/morePosts', PublicPostController::class . '@allPosts');

/** Страница поста в паблике */
$router->get( 'get', '/post/*' , PublicPostController::class . '@getPost');

/** Страница списка постов в паблике  */
$router->get( 'get', '/posts' , PublicPostController::class . '@allPosts');

/** Страница 404  */
$router->get('get','/404', NotFoundException::class . '@render');

/** подписка| отписка */
$router->get('post','/manage-subscribes/admin/subscribe', AdminSubscribeController::class. '@subscribe');
$router->get('post','/manage-subscribes/public/subscribe', PublicSubscribeController::class . '@subscribe');
$router->get('post','/manage-subscribes/unsubscribe', AdminSubscribeController::class . '@unsubscribe');
$router->get('get','/manage-subscribes/unsubscribe-by-link', PublicSubscribeController::class . '@unsubscribeByLink');

/**
 * Админские маршруты
 */
/** Управление постами и их изображениями */
$router->get('get','/admin/blog/posts', AdminPostController::class . '@listPosts');
$router->get('get','/admin/blog/posts/create', AdminPostController::class . '@createPost');
$router->get('get','/admin/blog/posts/*/edit', AdminPostController::class . '@editPost');
$router->get('post','/admin/blog/posts/save', AdminPostController::class . '@savePost');
$router->get('post','/admin/blog/posts/delete', AdminPostController::class . '@deletePost');
$router->get('post','/admin/blog/posts/img/upload', AdminPostController::class . '@imgUpload');
$router->get(['get', 'post'],'/admin/blog/posts/img/get', AdminPostController::class . '@getImages');

/** Управление личным аккаунтом  */
$router->get('get','/admin/account/edit', AdminAccountController::class . '@editUserProfileForm');
$router->get('get','/admin/account', AdminAccountController::class . '@getUserProfile');
$router->get('post','/admin/account/save', AdminAccountController::class . '@updateUserProfile');

/** Управление пользователями и их ролями  */
$router->get('get','/admin/user-manager', AdminUserManagerController::class . '@listUsers');
$router->get('post','/update/userdata/userChangeActivate', AdminUserManagerController::class . '@userChangeData');
$router->get('post','/update/userdata/userChangeRole', AdminUserManagerController::class . '@userChangeData');

/** Форма аутентификации  */
$router->get(['get', 'post'],'/login', LoginController::class . '@form');

/** Маршрут авторизации  */
$router->get('post','/admin/auth', LoginController::class . '@adminAuth');

/** Выход из аккаунта  */
$router->get('get','/logout', LoginController::class . '@logout');

/** Форма регистрации  */
$router->get('get','/signup', RegisterController::class . '@signUp');
$router->get('post','/register', RegisterController::class . '@registerUser');

/** Управление статическими страницами */
$router->get('get','/admin/static-pages', AdminStaticPagesController::class . '@index');
$router->get(['get', 'post'],'/admin/static-pages/add', AdminStaticPagesController::class . '@createPage');
$router->get('post','/admin/static-pages/edit', AdminStaticPagesController::class . '@editPage');
$router->get('post','/admin/static-pages/save', AdminStaticPagesController::class . '@savePage');
$router->get('post','/admin/static-pages/delete', AdminStaticPagesController::class . '@deletePage');

/** Добавление нового комментария */
$router->get('post','/blog/comments/add', PublicCommentController::class . '@addComment');

/** Список комментариев в админке */
$router->get('get','/admin/posts/comments', AdminCommentController::class . '@listComments');
$router->get('post','/admin/posts/comments/approve', AdminCommentController::class . '@toApproveComment');
$router->get('post','/admin/posts/comments/reject', AdminCommentController::class . '@toRejectComment');

/** Маршруты настроек бэкенда */
$router->get('get','/admin/settings', AdminSettingsController::class . '@index');
$router->get('post','/admin/settings/save', AdminSettingsController::class . '@saveSettings');

/** Маршрут Тостов */
$router->get('post', '/toasts/index', Toast::class . '@index');
$router->get('post','/checkToast', Toast::class . '@checkToast');

/** Маршруты статических страниц */
foreach ((new StaticPagesRepository())->getStaticPagesCollection() as $url => $page) {
    $router->get('get', $url, StaticPagesController::class . '@index');
}

return $router;