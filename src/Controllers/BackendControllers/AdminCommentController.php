<?php
/**
 * Класс AdminCommentController
 */
namespace App\Controllers\BackendControllers;

use App\Controllers\CommentRepository;
use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Model\Comment;
use App\Redirect;
use App\Validate\Validator;
use App\View;
use App\Controllers\ToastsController;

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

        if(!in_array($this->session->get('userRole'), ['admin', 'content-manager'])) {
            (new ToastsController())->setToast('info', 'У вас недостаточно прав для этого действия');
            Redirect::to('/');
        }
    }

    /**
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function addComment(): string
    {
        if($this->request->post() and $this->checkAuthorization() && checkToken()) {

            $commentDataToSave = [
                'user_id' => $this->session->get('userId'),
                'content' => strip_tags((string) $this->request->post('commentContent')),
            ];

            $commentDataToSave['has_moderated'] = (in_array((new UserController())
                                                ->getCurrentUser()
                                                ->role
                                                ->code, ['admin', 'content-manager'])) ?  1 : 0;

            // создаем валидатор
            $validator = (new Validator($commentDataToSave, Comment::class));

            // проверяем данные валидатором
            $resultValidateForms = $validator->makeValidation();

            if(empty($resultValidateForms['error'])) {
                $comment = new Comment($commentDataToSave);
                $post = (new PostController())->getPostById((int) $this->request->post('postId'));
                if($post !== null) {

                    $post->comments()->save($comment);

                    (new ToastsController())->setToast('success', 'Комментарий успешно сохранён.');

                    return json_encode([
                        'url' => '/post/' . $post->slug . '?#comments'
                    ]);

                } else {
                    return ToastsController::getToast('warning', 'Указанного поста не существует');
                }
            } else {
                return ToastsController::getToast('warning', 'Ошибка записи данных комментария');
            }
        } else {
            return ToastsController::getToast('warning', 'Нет входящих данных');
        }
    }

    /**
     * @return View
     */
    public function listComments(): View
    {

        $title = 'Модерация комментариев пользователей';
        $avatarPath = $this->session->get('config')->getConfig('avatars')['pathToUpload'];

        return new View('admin', [
            'view' => 'admin.comments.list',
            'data' => [
                'title' => $title,
                'comments' => (new CommentRepository())->getAllComments(),
                'token' => generateToken(),
                'avatarPath' => $avatarPath
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
            return ToastsController::getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedApprove((int) $this->request->post('commentId'));
        if($comment == null) {
            return ToastsController::getToast('warning', 'Комментарий не найден');
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
            return ToastsController::getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $commentRepository = new CommentRepository();
        $comment = $commentRepository->setHasModeratedReject((int) $this->request->post('commentId'));
        if($comment == null) {
            return ToastsController::getToast('warning', 'Комментарий не найден');
        }

        (new ToastsController())->setToast('success', 'Комментарий успешно отклонён');

        return json_encode([
            'url' => '/admin/posts/comments'
        ]);
    }

}
