<?php
/**
 * Class PublicPostController
 */

namespace App\Controllers\PublicControllers;

use App\Renderable;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\View;
use App\Config;
use App\Redirect;

use function Helpers\generateToken;
use function Helpers\parseRequestUri;

/**
 * Class PublicPostController
 * @package App\Controllers\PublicControllers
 */
class PublicPostController extends PublicController implements Renderable
{
    /**
     * @var mixed|null
     */
    private mixed $configImages;

    /**
     * @var string;
     */
    private $view;

    /**
     * @var array
     */
    private $data;

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
     */
    public function index()
    {
        $this->allPosts();
    }

    /**
     * Вывод всех опубликованных постов
     */
    public function allPosts()
    {
        $page = !empty($this->request->post('page')) ? $this->request->post('page') : 1;

        if(!empty($this->request->post('page'))) {

            $this->view = 'partials.posts_items';

            $this->data = [
                'posts' => (new PostRepository())->getAllPublishedPosts('desc', $page),
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'token' => generateToken(),
            ];
        } else {

            $this->data = [
                'view' => 'posts',
                'data' => [
                    'posts' => (new PostRepository())->getAllPublishedPosts('desc', $page),
                    'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                    'token' => generateToken(),
                ],
                'title' => 'Курсовая работа CMS для Блога'
            ];

            $this->view = 'index';
        }

        $this->render();
    }

    /**
     * Вывод списка последних постов
     * @param string $view
     */
    public function latestPosts(string $view = 'partials.latest_posts')
    {
        $this->view = $view;
        $this->data['title'] = 'Блог';
        $this->data['latestPosts'] = [
            'posts' => (new PostRepository())->getLatestPosts(),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        $this->render();
    }

    /**
     * Возвращает страницу с постом
     */
    public function getPost()
    {
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - название новости латиницей

        $post = (new PostRepository())->getPostBySlug($slug);
        if(!empty($post)) {

            $userRole = ($this->session->get('userId') !== null) ? (new UserRepository())->getCurrentUser()->role->code : 'none';

            $comments = new PublicCommentController();

            $this->data = [
                'view' => 'post',
                'data' => [
                    $module => $post,
                    'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                    'token' => generateToken(),
                    'userRole' => $userRole,
                    'comments' => $comments->getAllowableCommentsByPostId($post->id)
                ],
                'title' => 'Блог | ' . $post->title
            ];

            $this->view = 'index';
            $this->render();
        }

        Redirect::to('/404');
    }

    /**
     * Вывод данных в шаблон и отрисовка
     */
    public function render(): void
    {
        (new View($this->view, $this->data))->render();
    }

}
