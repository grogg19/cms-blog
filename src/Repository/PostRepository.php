<?php

namespace App\Repository;


use App\Model\Post;
use App\Model\Post as ModelPost;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Controllers\PublicControllers\PublicSettingsController;
use function Helpers\getCurrentDate;

/**
 * Class PostRepository
 * @package App\Repository
 */
class PostRepository extends Repository
{
    /**
     * выводит последние 2 поста
     * @return Collection
     */
    public function getLatestPosts(): Collection
    {
        return ModelPost::where('published', 1)
            ->where('published_at', '<=', getCurrentDate())
            ->orderBy('published_at', 'desc')
            ->take(2)
            ->get();
    }

    /**
     * Возвращает коллекцию всех постов
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
     * Возвращает все опубликованные посты
     * @param string $sortDirection
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function getAllPublishedPosts(string $sortDirection = 'desc', int $page = 1): LengthAwarePaginator
    {
        $perPage = (new PublicSettingsController())->getPreferencesByName('preferences');

        return ModelPost::with('images')
            ->where('published', 1)
            ->where('published_at', '<=', getCurrentDate())
            ->orderBy('published_at',$sortDirection)
            ->paginate($perPage->per_page, '*', 'page', $page);
    }

    /**
     * Возвращает пост по идентификатору slug
     * @param string $slug
     * @return ModelPost
     */
    public function getPostBySlug(string $slug): Post
    {
        return ModelPost::where('slug', $slug)->first();
    }

    /**
     * Возвращает пост по id
     * @param int $id
     * @return ModelPost
     */
    public function getPostById(int $id): Post
    {
        return ModelPost::find($id);
    }

    /**
     * Возвращает посты пользователя с userId с пагинацией
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
     * Возвращает экземпляр Post
     * @return ModelPost
     */
    public function createNewPost(): ModelPost
    {
        return new ModelPost();
    }

    /**
     * сохраняет объект в БД
     * @param ModelPost $post
     */
    public function savePost(ModelPost $post): void
    {
        $post->save();
    }

    /**
     * Удаление объекта из бд
     * @param ModelPost $post
     */
    public function deletePost(ModelPost $post): void
    {
        $post->delete();
    }
}
