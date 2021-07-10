<?php
/**
 * Класс AdminCommentController
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\CommentRepository;
use App\View;
use Illuminate\Pagination\LengthAwarePaginator;

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

        if (!$this->auth->checkPermissons(['admin', 'content-manager'])) {

            $this->toast->setToast('info', 'У вас недостаточно прав для этого действия');

            Redirect::to('/');
        }
    }

    /**
     * Рендер списка комментариев
     * @return Renderable
     */
    public function listComments(): Renderable
    {

        $title = 'Модерация комментариев пользователей';
        $avatarPath = $this->session->get('config')->getConfig('avatars')['pathToUpload'] . DIRECTORY_SEPARATOR;
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;
        $page = (!empty($this->request->get('page'))) ? filter_var($_GET['page'], FILTER_SANITIZE_NUMBER_INT): 1;

        $comments = (new CommentRepository())->getAllComments('asc', $quantity, $page);

        if ($comments instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $comments->setPath('comments' . $query);
        }

        $dataListComments = [
            'title' => $title,
            'comments' => $comments,
            'token' => generateToken(),
            'avatarPath' => $avatarPath,
            'quantity' => $quantity
        ];

        if ($quantity !== 'all') {
            $dataListComments['paginator'] = $comments;
        }

        $view = 'admin.comments.list';

        return new View($view, $dataListComments);
    }

    /**
     * Одобрение комментария
     * @return string
     */
    public function toApproveComment(): string
    {
        if (empty($this->request->post('commentId')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedApprove((int) $this->request->post('commentId'));
        if ($comment == null) {
            return $this->toast->getToast('warning', 'Комментарий не найден');
        }

        $this->toast->setToast('success', 'Комментарий успешно одобрен');

        return json_encode(['url' => '/admin/posts/comments']);

    }

    /**
     * Отменяет одобрение комментария
     * @return string
     */
    public function toRejectComment(): string
    {
        if (empty($this->request->post('commentId')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedReject((int) $this->request->post('commentId'));

        if ($comment == null) {
            return $this->toast->getToast('warning', 'Комментарий не найден');
        }

        $this->toast->setToast('success', 'Комментарий успешно отклонён');

        return json_encode(['url' => '/admin/posts/comments']);
    }
}
