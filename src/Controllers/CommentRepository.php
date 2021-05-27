<?php
/**
 * Класс CommentRepository
 */

namespace App\Controllers;

use App\Model\Comment;
use App\Redirect;

class CommentRepository
{
    /**
     * @param string $orderByDirection
     * @return array
     */
    public function getAllComments($orderByDirection = 'asc'): array
    {
        $comments = Comment::all();
        $sorted = ($orderByDirection = 'asc') ? $comments->sortBy('has_moderated') : $comments->sortByDesc('has_moderated');

        return $sorted->values()->all();
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function setHasModeratedApprove(int $id)
    {
        $comment = Comment::find($id);
        $comment->has_moderated = 1;
        $comment->save();

        return $comment;
    }

    /**
     * @param int $id
     * @return mixed
     */
    public function setHasModeratedReject(int $id)
    {
        $comment = Comment::find($id);
        $comment->has_moderated = 0;
        $comment->save();

        return $comment;
    }
}
