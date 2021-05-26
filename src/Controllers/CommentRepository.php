<?php
/**
 * Класс CommentRepository
 */

namespace App\Controllers;

use App\Model\Comment;

class CommentRepository
{
    private $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }

    public function getAllComments()
    {
       // return $this->comment->
    }
}
