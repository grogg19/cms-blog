<?php

use App\Controllers\PublicControllers\StaticPagesController;
use App\Router;

$router = new Router(); // создаем маршрутизатор

/** Главная страница с лентой постов  */
$router->get('get','/', 'Controllers\PublicControllers\PublicPostController@allPosts');

/** Добавочная загрузка постов в ленте  */
$router->get('post','/morePosts', 'Controllers\PublicControllers\PublicPostController@allPosts');

/** Страница поста в паблике */
$router->get( 'get', '/post/*' , 'Controllers\PublicControllers\PublicPostController@getPost');

/** Страница списка постов в паблике  */
$router->get( 'get', '/posts' , 'Controllers\PublicControllers\PublicPostController@allPosts');

/** Страница 404  */
$router->get('get','/404', 'Exception\NotFoundException@render');

/** подписка| отписка */
$router->get('post','/manage-subscribes/admin/subscribe', 'Controllers\BackendControllers\AdminSubscribeController@subscribe');
$router->get('post','/manage-subscribes/public/subscribe', 'Controllers\PublicControllers\PublicSubscribeController@subscribe');
$router->get('post','/manage-subscribes/unsubscribe', 'Controllers\BackendControllers\AdminSubscribeController@unsubscribe');
$router->get('get','/manage-subscribes/unsubscribe-by-link', 'Controllers\PublicControllers\PublicSubscribeController@unsubscribeByLink');

/**
 * Админские маршруты
 */
/** Управление постами и их изображениями */
$router->get('get','/admin/blog/posts', 'Controllers\BackendControllers\AdminPostController@listPosts');
$router->get('get','/admin/blog/posts/create', 'Controllers\BackendControllers\AdminPostController@createPost');
$router->get('get','/admin/blog/posts/*/edit', 'Controllers\BackendControllers\AdminPostController@editPost');
$router->get('post','/admin/blog/posts/save', 'Controllers\BackendControllers\AdminPostController@savePost');
$router->get('post','/admin/blog/posts/delete', 'Controllers\BackendControllers\AdminPostController@deletePost');
$router->get('post','/admin/blog/posts/img/upload', 'Controllers\BackendControllers\AdminPostController@imgUpload');
$router->get(['get', 'post'],'/admin/blog/posts/img/get', 'Controllers\BackendControllers\AdminPostController@getImages');

/** Управление личным аккаунтом  */
$router->get('get','/admin/account/edit', 'Controllers\BackendControllers\AdminAccountController@editUserProfileForm');
$router->get('get','/admin/account', 'Controllers\BackendControllers\AdminAccountController@getUserProfile');
$router->get('post','/admin/account/save', 'Controllers\BackendControllers\AdminAccountController@updateUserProfile');

/** Управление пользователями и их ролями  */
$router->get('get','/admin/user-manager', 'Controllers\BackendControllers\AdminUserManagerController@listUsers');
$router->get('post','/update/userdata/userChangeActivate', 'Controllers\BackendControllers\AdminUserManagerController@userChangeData');
$router->get('post','/update/userdata/userChangeRole', 'Controllers\BackendControllers\AdminUserManagerController@userChangeData');

/** Форма аутентификации  */
$router->get(['get', 'post'],'/login', 'Controllers\BackendControllers\LoginController@form');

/** Маршрут авторизации  */
$router->get('post','/admin/auth', 'Controllers\BackendControllers\LoginController@adminAuth');

/** Выход из аккаунта  */
$router->get('get','/logout', 'Controllers\BackendControllers\LoginController@logout');

/** Форма регистрации  */
$router->get('get','/signup', 'Controllers\BackendControllers\RegisterController@signUp');
$router->get('post','/register', 'Controllers\BackendControllers\RegisterController@registerUser');

/** Управление статическими страницами */
$router->get('get','/admin/static-pages', 'Controllers\BackendControllers\StaticPagesController@index');
$router->get(['get', 'post'],'/admin/static-pages/add', 'Controllers\BackendControllers\StaticPagesController@createPage');
$router->get('post','/admin/static-pages/edit', 'Controllers\BackendControllers\StaticPagesController@editPage');
$router->get('post','/admin/static-pages/save', 'Controllers\BackendControllers\StaticPagesController@savePage');
$router->get('post','/admin/static-pages/delete', 'Controllers\BackendControllers\StaticPagesController@deletePage');

/** Добавление нового комментария */
$router->get('post','/blog/comments/add', 'Controllers\PublicControllers\PublicCommentController@addComment');

/** Список комментариев в админке */
$router->get('get','/admin/posts/comments', 'Controllers\BackendControllers\AdminCommentController@listComments');
$router->get('post','/admin/posts/comments/approve', 'Controllers\BackendControllers\AdminCommentController@toApproveComment');
$router->get('post','/admin/posts/comments/reject', 'Controllers\BackendControllers\AdminCommentController@toRejectComment');

/** Маршруты настроек бэкенда */
$router->get('get','/admin/settings', 'Controllers\BackendControllers\AdminSettingsController@index');
$router->get('post','/admin/settings/save', 'Controllers\BackendControllers\AdminSettingsController@saveSettings');

/** Маршрут Тостов */
$router->get('post', '/toasts/index', 'Controllers\ToastsController@index');
$router->get('post','/checkToast', 'Controllers\ToastsController@checkToast');

/** Маршруты статических страниц */
foreach ((new StaticPagesController())->getStaticPages() as $url => $page) {
    $router->get('get', $url, 'Controllers\PublicControllers\StaticPagesController@index');
}

return $router;