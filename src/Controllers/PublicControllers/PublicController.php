<?php
/**
 * Class PublicController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Repository\PostRepository;
use App\Repository\UserRepository;

class PublicController extends Controller
{
    /**
     * PublicController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->data['latestPosts'] = (new PostRepository())->getLatestPosts(); // свежие посты

        $this->data['user'] = (session_status() === 2) ? (new UserRepository())->getCurrentUser() : null; // текущий пользователь
    }
}
