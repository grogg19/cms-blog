<?php

use App\Controllers\PublicControllers\StaticPagesController;
use App\Router;

$router = new Router(); // создаем маршрутизатор

$router->get('get','/', 'Controllers\PublicControllers\PublicPostController@index');

$router->get('get','/test', 'Controllers\PublicControllers\TestController@test');
$router->get( 'get', '/test/*/test2/*' , 'Controller@test');

//Маршрут на страницу /about
$router->get('get','/about', 'Controller::about');
$router->get( 'get', '/post/*' , 'Controllers\PublicControllers\PublicPostController@getPost');
$router->get( 'get', '/posts' , 'Controllers\PublicControllers\PublicPostController@index');
$router->get('get','/404', 'Exception\NotFoundException@render');

/**
 * Админские маршруты
 */

$router->get('get','/admin/blog/posts', 'Controllers\BackendControllers\AdminPostController@listPosts');
$router->get('get','/admin/blog/posts/create', 'Controllers\BackendControllers\AdminPostController@createPost');
$router->get('get','/admin/blog/posts/*/edit', 'Controllers\BackendControllers\AdminPostController@editPost');
$router->get('post','/admin/blog/posts/save', 'Controllers\BackendControllers\AdminPostController@savePost');
$router->get('post','/admin/blog/posts/delete', 'Controllers\BackendControllers\AdminPostController@deletePost');
$router->get('post','/admin/blog/posts/img/upload', 'Controllers\BackendControllers\AdminPostController@imgUpload');
$router->get(['get', 'post'],'/admin/blog/posts/img/get', 'Controllers\BackendControllers\AdminPostController@getImages');

$router->get('get','/admin/account/edit', 'Controllers\BackendControllers\AdminAccountController@editUserProfileForm');
$router->get('get','/admin/account', 'Controllers\BackendControllers\AdminAccountController@getUserProfile');
$router->get('post','/admin/account/save', 'Controllers\BackendControllers\AdminAccountController@updateUserProfile');

$router->get('get','/admin/user-manager', 'Controllers\BackendControllers\AdminUserManagerController@index');
$router->get('post','/update/userdata/userChangeActivate', 'Controllers\BackendControllers\AdminUserManagerController@userChangeData');
$router->get('post','/update/userdata/userChangeRole', 'Controllers\BackendControllers\AdminUserManagerController@userChangeData');

$router->get(['get', 'post'],'/login', 'Controllers\BackendControllers\LoginController@form');
$router->get('post','/admin/auth', 'Controllers\BackendControllers\LoginController@adminAuth');
$router->get(['get', 'post'],'/admin/test', 'Auth\Auth@test');
$router->get('get','/logout', 'Controllers\BackendControllers\LoginController@logout');
$router->get(['get', 'post'],'/signup', 'Controllers\BackendControllers\RegisterController@signup');

$router->get('get','/admin/static-pages', 'Controllers\BackendControllers\StaticPagesController@index');
$router->get(['get', 'post'],'/admin/static-pages/add', 'Controllers\BackendControllers\StaticPagesController@createPage');
$router->get('post','/admin/static-pages/edit', 'Controllers\BackendControllers\StaticPagesController@editPage');
$router->get('post','/admin/static-pages/save', 'Controllers\BackendControllers\StaticPagesController@savePage');
$router->get('post','/admin/static-pages/delete', 'Controllers\BackendControllers\StaticPagesController@deletePage');

/** Добавление нового комментария */
$router->get('post','/blog/comments/add', 'Controllers\BackendControllers\AdminCommentController@addComment');

/** маршрут Тостов */
$router->get('post', '/toasts/index', 'Controllers\ToastsController@index');

/** статических страниц */

foreach ((new StaticPagesController())->getStaticPages() as $url => $page) {
    $router->get('get', $url, 'Controllers\PublicControllers\StaticPagesController@index');
}

return $router;