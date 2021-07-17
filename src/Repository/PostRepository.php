<?php

namespace App\Repository;

use App\Cookie\Cookie;
use App\Model\Image;
use App\Model\User;
use App\Model\Post as ModelPost;
use App\Request\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Controllers\PublicControllers\PublicSettingsController;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @param int $page
     * @return mixed
     */
    public function getAllPosts(string $sortDirection = 'desc', string $quantity = '20', int $page = 1)
    {
        if ($quantity !== 'all') {

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
    public function getPostBySlug(string $slug): ModelPost
    {
        return ModelPost::where('slug', $slug)->first();
    }

    /**
     * Возвращает пост по id
     * @param int $id
     * @return ModelPost
     */
    public function getPostById(int $id): ModelPost
    {
        return ModelPost::find($id);
    }

    /**
     * Возвращает посты пользователя с userId с пагинацией
     * @param int $userId
     * @param string $quantity
     * @param string $sortDirection
     * @param int $page
     * @return LengthAwarePaginator|Collection
     */
    public function getPostsByUserId(int $userId, string $quantity, string $sortDirection = 'desc', int $page = 1)
    {
        if ($quantity !== 'all') {

            return ModelPost::where('user_id', $userId)
                ->orderBy('published_at',$sortDirection)
                ->paginate($quantity, '*', 'page', $page);
        }

        return ModelPost::where('user_id', $userId)
            ->orderBy('published_at',$sortDirection)
            ->get();
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

    /**
     * Запись данных в модель
     * @param Request $request
     * @param User|null $user
     * @return ModelPost
     */
    public function saveToDb(Request $request, Session $session, User $user = null): ModelPost
    {
        if (!empty($request->post('idPost'))) {
            $post = $this->getPostById((int) $request->post('idPost'));
        } else {
            $post = $this->createNewPost();
            $post->user_id = $user->id;
        }

        $post->title = filter_var($request->post('title'), FILTER_SANITIZE_STRING);
        $post->slug = filter_var(trim($request->post('slug'), '/'), FILTER_SANITIZE_STRING);
        $post->excerpt = filter_var($request->post('excerpt'), FILTER_SANITIZE_STRING);
        $post->content = cleanJSTags( (string) $request->post('content'));
        $post->published = (!empty($request->post('published')) && $request->post('published') == 'on') ? 1 : 0;
        $post->published_at = (!empty($request->post('published_at'))) ? getDateTimeForDb($request->post('published_at')) : "";

        $post->save();

        if (!empty(Cookie::getArray('uploadImages'))) {

            $sort = 0;
            $configImages = $session->get('config')->getConfig('images');

            $arrayCookieImages = Cookie::getArray('uploadImages')[key(Cookie::getArray('uploadImages'))];

            foreach ($arrayCookieImages as $imageFileName) {
                $pathToFile = $_SERVER['DOCUMENT_ROOT'] . $configImages['pathToUpload'] . DIRECTORY_SEPARATOR . $imageFileName;

                if (file_exists($pathToFile)) {
                    $data[] = [
                        'file_name' => $imageFileName,
                        'post_id' => $post->id,
                        'sort' => $sort++
                    ];
                }
            }

            Image::insert($data); // Добавляем изображения в БД

            Cookie::delete('uploadImages'); // Чистим список загруженных изображений в куках
        }
        return $post;
    }
}
