<?php
/**
 * Класс CommentRepository
 */

namespace App\Controllers;

use App\Model\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class CommentRepository
 * @package App\Controllers
 */
class CommentRepository
{
    /** Возвращает коллекцию коммент с пагинацией
     * @param string $orderByDirection
     * @param string $quantity
     * @param int $page
     * @return LengthAwarePaginator|Collection
     */
    public function getAllComments(string $orderByDirection = 'asc', string $quantity = '20', int $page = 1): LengthAwarePaginator|Collection
    {
        if($quantity == 'all') {
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
}
