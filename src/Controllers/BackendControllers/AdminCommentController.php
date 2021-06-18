<?php
/**
 * Класс AdminCommentController
 */
namespace App\Controllers\BackendControllers;

use App\Repository\CommentRepository;
use App\View;
use App\Controllers\ToastsController;

use Illuminate\Pagination\LengthAwarePaginator;
use function Helpers\checkToken;
use function Helpers\generateToken;

/**
 * Class AdminCommentController
 * @package App\Controllers\BackendControllers
 */
class AdminCommentController extends AdminController
{
    /**
     * AdminCommentController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth->checkPermissons(['admin', 'content-manager']);
    }

    /**
     * @return View
     */
    public function listComments(): View
    {

        $title = 'Модерация комментариев пользователей';
        $avatarPath = $this->session->get('config')->getConfig('avatars')['pathToUpload'];
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;
        $page = (!empty($this->request->get('page'))) ? filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT): 1;

        $comments = (new CommentRepository())->getAllComments('asc', $quantity, $page);

        if($comments instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $comments->setPath('comments' . $query);
        }

        return new View('admin', [
            'view' => 'admin.comments.list',
            'data' => [
                'title' => $title,
                'comments' => $comments,
                'token' => generateToken(),
                'avatarPath' => $avatarPath,
                'quantity' => $quantity
            ],
            'title' => 'Администрирование | ' . $title
        ]);
    }

    /**
     * @return string
     */
    public function toApproveComment(): string
    {
        if(empty($this->request->post('commentId')) || !checkToken()) {
            return (new ToastsController())->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedApprove((int) $this->request->post('commentId'));
        if($comment == null) {
            return (new ToastsController())->getToast('warning', 'Комментарий не найден');
        }

        (new ToastsController())->setToast('success', 'Комментарий успешно одобрен');

        return json_encode([
            'url' => '/admin/posts/comments'
        ]);

    }

    /**
     * @return string
     */
    public function toRejectComment(): string
    {
        if(empty($this->request->post('commentId')) || !checkToken()) {
            return (new ToastsController())->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedReject((int) $this->request->post('commentId'));

        if($comment == null) {
            return (new ToastsController())->getToast('warning', 'Комментарий не найден');
        }

        (new ToastsController())->setToast('success', 'Комментарий успешно отклонён');

        return json_encode([
            'url' => '/admin/posts/comments'
        ]);
    }

}
