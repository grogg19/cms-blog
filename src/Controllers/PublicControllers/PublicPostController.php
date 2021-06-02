<?php
/**
 * Class PublicPostController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\UserController;
use App\View;
use App\Controllers\PostController;
use App\Config;
use App\Redirect;

use function Helpers\generateToken;
use function Helpers\parseRequestUri;

/**
 * Class PublicPostController
 * @package App\Controllers\PublicControllers
 */
class PublicPostController extends PublicController
{
    /**
     * @var mixed|null
     */
    private mixed $configImages;

    /**
     * PublicPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    /**
     * Выводит все посты на странице
     * @return View
     */
    public function index()
    {
        return $this->allPosts();
    }

    /**
     * Вывод всех опубликованных постов
     * @return View
     */
    public function allPosts(): View
    {
        $page = !empty($this->request->post('page')) ? $this->request->post('page') : 1;

        $data = [
            'posts' => (new PostController())->getAllPublishedPosts('desc', $page),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
            'token' => generateToken()
        ];

        if(!empty($this->request->post('page'))) {

            return new View('partials.posts_items', [
                'posts' => $data['posts'],
                'imgPath' => $data['imgPath']
            ]);
        }
        return new View('index', [
            'view' => 'posts',
            'data' => $data,
            'title' => 'Блог'
        ]);
    }

    /**
     * Вывод списка последних постов
     * @return array
     */
    public function latestPosts(): array
    {
        $data = [
            'posts' => (new PostController())->getLatestPosts(),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return ['view' => 'partials/latest_posts', 'latestPosts' => $data];
    }

    /**
     * Возвращает страницу с постом
     * @return View
     */
    public function getPost(): View
    {
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - название новости латиницей

        $post = (new PostController())->getPostBySlug($slug);
        if(!empty($post)) {

            $userRole = ($this->session->get('userId') !== null) ? (new UserController())->getCurrentUser()->role->code : 'none';

            $comments = new PublicCommentController();

            $data = [
                $module => $post,
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'userRole' => $userRole,
                'comments' => $comments->getAllowableCommentsByPostId($post->id),
                'token' => generateToken()
            ];

            return new View('index', [
                'view' => 'post',
                'data' => $data,
                'title' => 'Блог | ' . $post->title
            ]);
        }

        Redirect::to('/404');
    }

}
