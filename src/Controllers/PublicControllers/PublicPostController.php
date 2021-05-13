<?php
/**
 * Class PublicPostController
 */

namespace App\Controllers\PublicControllers;

use App\View;
use App\Controllers\PostController;
use App\Config;
use App\Redirect;

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
        $data = [
            'posts' => (new PostController())->getAllPublishedPosts(),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return new View('index', ['view' => 'posts', 'data' => $data, 'title' => 'Блог']);
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
            $data = [
                $module => $post,
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
            ];

            return new View('index', ['view' => 'post', 'data' => $data, 'title' => 'Блог | ' . $post->title ]);
        }

        Redirect::to('/404');
    }

}
