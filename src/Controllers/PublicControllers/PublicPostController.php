<?php
/**
 * Class PublicPostController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Post;
use App\DI\DI;
use App\View;
use App\Controllers\PostController;
use App\Config;

use function Helpers\parseRequestUri;
use function Helpers\printArray;

class PublicPostController extends PublicController
{
    private $configImages;

    public function __construct()
    {
        parent::__construct();
        $this->configImages = Config::getInstance()->getConfig('images');
    }

    public function index()
    {
        return $this->allPosts();
    }


    public function allPosts()
    {

        $data = [
            'posts' => (new PostController())->getAllPublishedPosts(),
            'title' => 'Блог',
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return new View('index', ['view' => 'posts', 'data' => $data]);
    }

    public function latestPosts()
    {
        $data = [
            'posts' => (new PostController())->getLatestPosts(),
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return ['view' => 'partials/latest_posts', 'latestPosts' => $data];
    }

    public function getPost()
    {
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - английское название новости

        $post = (new PostController())->getPostBySlug($slug);
        $data = [
            $module => $post,
            'title' => 'Блог | ' . $post->title,
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return new View('index', ['view' => 'post', 'data' => $data]);
    }

}