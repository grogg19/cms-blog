<?php

namespace App\Controllers\PublicControllers;

use App\Auth\Auth;
use App\Jsonable;
use App\Renderable;
use App\Repository\PostRepository;
use App\Controllers\ToastsController;
use App\Repository\UserRepository;
use App\Model\Comment;
use App\Validate\Validator;
use App\View;
use Illuminate\Database\Eloquent\Collection;

use function Helpers\checkToken;

/**
 * Class PublicCommentController
 * @package App\Controllers\PublicControllers
 */
class PublicCommentController extends PublicController implements Renderable, Jsonable
{
    /**
     * @var string
     */
    private $view;

    /**
     * @var array
     */
    private $data;

    /**
     * Возвращает комментарий по его id
     * @param int $id
     * @return Comment
     */
    public function getCommentById(int $id): Comment
    {
        return Comment::find($id);
    }

    /**
     * Возвращает все комментарии поста
     * @param int $postId
     * @return Collection
     */
    public function getAllCommentsByPostId(int $postId): Collection
    {
        return Comment::where('post_id', $postId)->get();
    }

    /**
     * возвращает все проверенные комментарии поста
     * @param int $postId
     * @return Collection
     */
    public function getAllModeratedCommentsByPostId(int $postId): Collection
    {
        $userId = !empty($this->session->get('userId')) ? $this->session->get('userId') : 0;

        return Comment::where(function ($query) use ($postId) {
            $query->where('has_moderated', 1)
                  ->where('post_id', $postId);
        })
            ->orWhere(function($query) use ($postId, $userId) {
                    $query->where('post_id', $postId)
                          ->where('user_id', $userId);
            })
            ->get();
    }

    /**
     * Возвращает коллекцию доступных комментариев для поста с postId
     * @param $postId
     * @return Collection
     */
    public function getAllowableCommentsByPostId($postId): Collection
    {
        if((new PublicUserController())->checkUserForComments()) {
            return $this->getAllCommentsByPostId($postId);
        }
        return $this->getAllModeratedCommentsByPostId($postId);
    }

    /**
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function addComment(): string
    {

        if(!(new Auth())->checkAuthorization()) {
            return (new ToastsController())->getToast('warning', 'Оставлять комментарии могут только зарегистрированные пользователи');
        }

        if(empty($this->request->post()) || !checkToken()) {
            return (new ToastsController())->getToast('warning', 'Нет входящих данных');
        }

        $commentDataToSave = [
            'user_id' => $this->session->get('userId'),
            'content' => strip_tags((string) $this->request->post('commentContent')),
        ];

        $commentDataToSave['has_moderated'] = (in_array((new UserRepository())
            ->getCurrentUser()
            ->role
            ->code, ['admin', 'content-manager'])) ?  1 : 0;

        // создаем валидатор
        $validator = (new Validator($commentDataToSave, Comment::class));

        // проверяем данные валидатором
        $resultValidateForms = $validator->makeValidation();

        // если есть ошибки, возвращаем Тост с ошибкой
        if(!empty($resultValidateForms['error'])) {
            return (new ToastsController())->getToast('warning', 'Ошибка записи данных комментария');
        }

        $comment = new Comment($commentDataToSave);
        $post = (new PostRepository())->getPostById((int) $this->request->post('postId'));

        if($post !== null) {

            $post->comments()->save($comment);

            (new ToastsController())->setToast('success', 'Комментарий успешно сохранён.');

            $this->data = [
                'url' => '/post/' . $post->slug
            ];

            return $this->json();

        }

        return (new ToastsController())->getToast('warning', 'Указанного поста не существует');
    }

    /**
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->data);
    }

    /**
     * Вывод данных в шаблон и отрисовка
     */
    public function render(): void
    {
        (new View($this->view, $this->data))->render();
    }

}
