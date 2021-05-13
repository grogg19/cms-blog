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
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
        ];

        return new View('index', ['view' => 'posts', 'data' => $data, 'title' => 'Блог']);
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