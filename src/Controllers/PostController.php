<?php
/**
 * Controller PostController
 */

namespace App\Controllers;

use App\Model\Post as ModelPost;

use App\Controllers\Controller as Controller;

/**
 * Class PostController
 * @package App\Controllers
 */
class PostController extends Controller
{

    /**
     * PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Метод выводит строку с параметрами из $_SERVER['REQUEST_URI']
     * @return string
     */
    public function getLatestPosts()
    {
        return ModelPost::where('published', 1)->orderBy('published_at', 'desc')->take(2)->get();
    }

    /**
     * @param string $sortDirection
     * @return mixed
     */
    public function getAllPosts(string $sortDirection = 'desc')
    {
        return ModelPost::orderBy('published_at',$sortDirection)
                            ->get();
    }

    /**
     * @param string $sortDirection
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllPublishedPosts(string $sortDirection = 'desc')
    {
        return ModelPost::with('images')
            ->where('published', 1)
            ->orderBy('published_at',$sortDirection)
            ->get();
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function getPostBySlug(string $slug)
    {
        return ModelPost::where('slug', $slug)->first();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function getPostById(int $id)
    {
        return ModelPost::where('id', $id)->first();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getPostsByUserId(int $userId)
    {
        return ModelPost::where('user_id', $userId)->get();
    }

    /**
     * @return ModelPost
     */
    public function createNewPost(): ModelPost
    {
        return new ModelPost();
    }


    public function listPostsRender() {
        //return Auth::checkRole($this->getPosts())->render();
    }

    /**
     * @param ModelPost $post
     */
    public function savePost(ModelPost $post)
    {
        $post->save();
    }
}
