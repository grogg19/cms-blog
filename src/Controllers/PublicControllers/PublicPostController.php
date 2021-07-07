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
    private $configImages;

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

        $this->configImages = Config::getInstance()->getConfig('images');
        $this->postRepository = new PostRepository();

    }

    /**
     * Вывод всех опубликованных постов
     * @return Renderable
     */
    public function allPosts(): Renderable
    {
        $page = !empty($this->request->post('page')) ? $this->request->post('page') : 1;

        if(!empty($this->request->post('page'))) {

            $this->view = 'partials.posts_items';
            $postsData = [
                    'posts' => $this->postRepository->getAllPublishedPosts('desc', $page),
                    'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                    'token' => generateToken()
                ];
        } else {
            $this->view = 'posts';
            $postsData = [
                'title' => 'Курсовая работа CMS для Блога',
                'posts' => $this->postRepository->getAllPublishedPosts('desc', $page),
                'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
                'token' => generateToken(),
            ];
        }

        $this->data = array_merge($this->data, $postsData);

        return new View($this->view, $this->data);
    }

    /**
     * Вывод списка последних постов
     * @param string $view
     * @return Renderable
     */
    public function latestPosts(string $view = 'partials.latest_posts'): Renderable
    {
        $data = [
            'latestPosts' => $this->postRepository->getLatestPosts(),
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
        list($module, $slug) = parseRequestUri(); // $module - каталог, $slug - название новости латиницей

        $post = $this->postRepository->getPostBySlug($slug);

        $userRepository = new UserRepository();
        $avatarPath = $userRepository->getUserAvatarPath();

        if(empty($post)) {
            Redirect::to('/404');
        }

        if(session_status() == 2) {
            $user = $this->data['user'];
            $userRole = $user->role->code;
        }

        $comments = new CommentRepository();

        $postData = [
            $module => $post,
            'imgPath' => $this->configImages['pathToUpload'] . DIRECTORY_SEPARATOR,
            'token' => generateToken(),
            'userRole' => (!empty($userRole)) ? $userRole: 'none',
            'comments' => $comments->getAllowableCommentsByPostId($post->id),
            'title' => 'Блог | ' . $post->title,
            'postId' => $post->id,
            'avatarPath' => $avatarPath
        ];

        $this->view = 'post';
        $this->data = array_merge($this->data, $postData);

        return new View($this->view, $this->data);
    }

}
