<?php
/**
 * Controller PostController
 */

namespace App\Controllers;

use App\Controllers\PublicControllers\PublicSettingsController;
use App\Model\Post as ModelPost;

use App\Controllers\Controller as Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

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
     * @param string $quantity
     * @return LengthAwarePaginator|Collection
     */
    public function getAllPosts(string $sortDirection = 'desc', string $quantity = '20'): LengthAwarePaginator|Collection
    {
        if($quantity !== 'all') {
            $page = empty($this->request->get('page')) ? 1 : $this->request->get('page');

            return ModelPost::orderBy('published_at',$sortDirection)
                ->paginate($quantity, '*', 'page', $page);
        }

        return ModelPost::orderBy('published_at',$sortDirection)
            ->get();

    }

    /**
     * @param string $sortDirection
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getAllPublishedPosts(string $sortDirection = 'desc', int $page = 1): LengthAwarePaginator
    {
        $perPage = (new PublicSettingsController())->getPreferencesByName('preferences');

        return ModelPost::with('images')
            ->where('published', 1)
            ->orderBy('published_at',$sortDirection)
            ->paginate($perPage->per_page, '*', 'page', $page);
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
        return ModelPost::find($id);
    }

    /**
     * @param int $userId
     * @param string $quantity
     * @return LengthAwarePaginator
     */
    public function getPostsByUserId(int $userId, string $quantity): LengthAwarePaginator
    {
        $page = empty($this->request->get('page')) ? 1 : $this->request->get('page');

        return ModelPost::where('user_id', $userId)->paginate($quantity, '*', 'page', $page);
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

    /**
     * Удаление записи
     * @param ModelPost $post
     */
    public function deletePost(ModelPost $post)
    {
        $post->delete();
    }

}
