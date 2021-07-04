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

/**
 * Class PublicPostController
 * @package App\Controllers\PublicControllers
 */
class PublicPostController extends PublicController
{
    /**
     * @var mixed
     */
    private static $configImages;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * PublicPostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        self::$configImages = Config::getInstance()->getConfig('images');
        $this->postRepository = new PostRepository();

    }

    /**
     * Вывод всех опубликованных постов
     * @return Renderable
     */
    public function allPosts(): Renderable
    {
        $page = !empty($this->request->post('page')) ? $this->request->post('page') : 1;

        $imgPath = self::$configImages['pathToUpload'] . DIRECTORY_SEPARATOR;

        if(!empty($this->request->post('page'))) {
            $view = 'partials.posts_items';
            $postsData = [
                    'posts' => $this->postRepository->getAllPublishedPosts('desc', $page),
                    'imgPath' => $imgPath,
                    'ajax' => true
                ];
        } else {
            $view = 'posts';
            $postsData = [
                'posts' => $this->postRepository->getAllPublishedPosts('desc', $page),
                'imgPath' => $imgPath,
            ];
        }

        $mergeData = [
            'title' => 'Курсовая работа CMS для Блога',
            'imgPath' => $imgPath,
            'token' => generateToken(),
            'latestPosts' => (new PostRepository())->getLatestPosts(),
            'user' => (session_status() === 2) ? (new UserRepository())->getCurrentUser() : null // текущий пользователь
        ];

        return new View($view, $postsData, $mergeData);
    }

    /**
     * Возвращает страницу с постом
     * @return Renderable
     */
    public function getPost(): Renderable
    {
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - название новости латиницей

        $post = $this->postRepository->getPostBySlug($slug);

        $userRepository = new UserRepository();
        $avatarPath = $userRepository->getUserAvatarPath();
        $imgPath = getImagesWebPath();

        if(empty($post)) {
            Redirect::to('/404');
        }

        if(session_status() == 2) {
            $user = $userRepository->getCurrentUser();
            $userRole = $user->role->code;
        }

        $comments = new CommentRepository();


        $postData = [
            $module => $post,
            'imgPath' => $imgPath,
            'userRole' => (!empty($userRole)) ? $userRole: 'none',
            'comments' => $comments->getAllowableCommentsByPostId($post->id),
            'postId' => $post->id,
            'avatarPath' => $avatarPath,
            'token' => generateToken()
        ];

        $mergeData = [
            'title' => 'Блог | ' . $post->title,
            'imgPath' => $imgPath,
            'token' => generateToken(),
            'latestPosts' => (new PostRepository())->getLatestPosts(),
            'user' => (!empty($user)) ? $user : null
        ];

        $view = 'post';

        return new View($view, $postData, $mergeData);
    }

}
