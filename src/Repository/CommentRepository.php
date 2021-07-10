<?php
/**
 * Класс CommentRepository
 */

namespace App\Repository;

use App\Controllers\PublicControllers\PublicUserController;
use App\Model\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;


/**
 * Class CommentRepository
 * @package App\Controllers
 */
class CommentRepository extends Repository
{
    /** Возвращает коллекцию коммент с пагинацией
     * @param string $orderByDirection
     * @param string $quantity
     * @param int $page
     * @return LengthAwarePaginator|Collection
     */
    public function getAllComments(string $orderByDirection = 'asc', string $quantity = '20', int $page = 1)
    {
        if ($quantity == 'all') {
            return Comment::orderBy('has_moderated',$orderByDirection)->get();
        }

        return Comment::orderBy('has_moderated',$orderByDirection)
            ->paginate($quantity, '*', 'page', $page);
    }

    /**
     * Устанавливает статус комментария "проверен"
     * @param int $id
     * @return bool
     */
    public function setHasModeratedApprove(int $id): bool
    {
        $comment = Comment::find($id);
        $comment->has_moderated = 1;

        return $comment->save();
    }

    /**
     * Устанавливает статус комментария "отклонен"
     * @param int $id
     * @return bool
     */
    public function setHasModeratedReject(int $id): bool
    {
        $comment = Comment::find($id);
        $comment->has_moderated = 0;

        return $comment->save();
    }

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
     * @return \Illuminate\Database\Eloquent\Collection
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
        $userId = session_status() === 2 ? $this->session->get('userId') : 0;

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

        if ((new PublicUserController())->checkUserForComments()) {

            return $this->getAllCommentsByPostId($postId);
        }
        return $this->getAllModeratedCommentsByPostId($postId);
    }
}
