<?php
/**
 * Класс AdminCommentController
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\CommentRepository;
use App\Request\Request;
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
     * @param Request $request
     * @return Renderable
     */
    public function listComments(Request $request): Renderable
    {

        $title = 'Модерация комментариев пользователей';
        $avatarPath = session()->get('config')->getConfig('avatars')['pathToUpload'] . DIRECTORY_SEPARATOR;
        $quantity = (!empty($request->get('quantity'))) ? filter_var($request->get('quantity'), FILTER_SANITIZE_STRING) : 20;
        $page = (!empty($request->get('page'))) ? filter_var($request->get('page'), FILTER_SANITIZE_NUMBER_INT): 1;

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

        return new View('admin.comments.list', $dataListComments);
    }

    /**
     * Одобрение комментария
     * @param Request $request
     * @return string
     */
    public function toApproveComment(Request $request): string
    {
        if (empty($request->post('commentId')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedApprove((int) $request->post('commentId'));
        if ($comment == null) {
            return $this->toast->getToast('warning', 'Комментарий не найден');
        }

        $this->toast->setToast('success', 'Комментарий успешно одобрен');

        return json_encode(['url' => '/admin/posts/comments']);

    }

    /**
     * Отменяет одобрение комментария
     * @param Request $request
     * @return string
     */
    public function toRejectComment(Request $request): string
    {
        if (empty($request->post('commentId')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedReject((int) $request->post('commentId'));

        if ($comment == null) {
            return $this->toast->getToast('warning', 'Комментарий не найден');
        }

        $this->toast->setToast('success', 'Комментарий успешно отклонён');

        return json_encode(['url' => '/admin/posts/comments']);
    }
}
