<?php
/**
 * Class PublicPostController
 */

namespace App\Controllers\PublicControllers;

use App\Renderable;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Config;
use App\Redirect;
use App\View;

use function Helpers\generateToken;
use function Helpers\parseRequestUri;

/**
 * Class PublicPostController
 * @package App\Controllers\PublicControllers
 */
class PublicPostController extends PublicController
{
    /**
     * @var mixed
     */
    private array $configImages;


    /**
     * PublicPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    /**
     * Вывод всех опубликованных постов
     * @return Renderable
     */
    public function allPosts(): Renderable
    {
        $page = !empty($this->request->post('page')) ? $this->request->post('page') : 1;

        if(!empty($this->request->post('page'))) {

            $view = 'partials.posts_items';
            $data['data'] = [
                'posts' => (new PostRepository())->getAllPublishedPosts('desc', $page),
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'token' => generateToken(),
            ];

        } else {
            $view = 'posts';
            $data = [
                'title' => 'Курсовая работа CMS для Блога',
                'posts' => (new PostRepository())->getAllPublishedPosts('desc', $page),
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'token' => generateToken(),
            ];

        }

        $data['user'] = (session_status() === 2) ? (new UserRepository())->getCurrentUser() : null;

        return new View($view, $data);
    }

    /**
     * Вывод списка последних постов
     * @param string $view
     * @return Renderable
     */
    public function latestPosts(string $view = 'partials.latest_posts'): Renderable
    {
        $data['title'] = 'Блог';
        $data['latestPosts'] = [
            'posts' => (new PostRepository())->getLatestPosts(),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return new View($view, $data);
    }

    /**
     * Возвращает страницу с постом
     * @return Renderable
     */
    public function getPost(): Renderable
    {
        $data = []; // Данные для View
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - название новости латиницей

        $post = (new PostRepository())->getPostBySlug($slug);

        if(empty($post)) {
            Redirect::to('/404');
        }

        if(!empty($post)) {

            $userRole = (session_status() == 2) ? (new UserRepository())->getCurrentUser()->role->code : 'none';

            $comments = new CommentRepository();

            $data = [
                $module => $post,
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'token' => generateToken(),
                'userRole' => $userRole,
                'comments' => $comments->getAllowableCommentsByPostId($post->id),
                'title' => 'Блог | ' . $post->title
            ];

            $data['user'] = (session_status() === 2) ? (new UserRepository())->getCurrentUser() : null;
        }
        return new View('post', $data);
    }

}
