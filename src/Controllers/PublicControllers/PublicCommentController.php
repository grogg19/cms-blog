<?php

namespace App\Controllers\PublicControllers;

use App\Model\Comment;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PublicCommentController
 * @package App\Controllers\PublicControllers
 */
class PublicCommentController extends PublicController
{
    /**
     * PublicCommentController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param int $id
     * @return Comment
     */
    public function getCommentById(int $id): Comment
    {
        return Comment::find($id);
    }

    /**
     * @param int $postId
     * @return Collection
     */
    public function getAllCommentsByPostId(int $postId): Collection
    {
        return Comment::where('post_id', $postId)->get();
    }

    /**
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

}
