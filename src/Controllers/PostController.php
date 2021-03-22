<?php
/**
 * Controller PostController
 */

namespace App\Controllers;

use App\DI\DI;
use App\Model\Post as ModelPost;

use App\Controllers\Controller as Controller;


class PostController extends Controller
{

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

    public function getAllPosts(string $sortDirection = 'desc')
    {
        return ModelPost::orderBy('published_at',$sortDirection)
                            ->get();
    }

    public function getAllPublishedPosts(string $sortDirection = 'desc')
    {
        return ModelPost::with('images')
            ->where('published', 1)
            ->orderBy('published_at',$sortDirection)
            ->get();
    }

    public function getPostBySlug(string $slug)
    {
        return ModelPost::where('slug', $slug)->first();
    }

    public function getPostById(int $id)
    {
        return ModelPost::where('id', $id)->first();
    }

    public function getPostsByUserId(int $userId)
    {
        return ModelPost::where('user_id', $userId)->get();
    }

    public function createNewPost(): ModelPost
    {
        return new ModelPost();
    }

    public function listPostsRender() {
        //return Auth::checkRole($this->getPosts())->render();
    }

    public function savePost(ModelPost $post)
    {
        $post->save();
    }

}
