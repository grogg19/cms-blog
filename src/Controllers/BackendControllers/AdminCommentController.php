<?php
/**
 * Класс AdminCommentController
 */
namespace App\Controllers\BackendControllers;

use App\Controllers\PostController;
use App\Controllers\UserController;
use App\Model\Comment;
use App\Validate\Validator;

use function Helpers\checkToken;

/**
 * Class AdminCommentController
 * @package App\Controllers\BackendControllers
 */
class AdminCommentController extends AdminController
{
    protected $user;
    protected $comment;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return false|string
     * @throws \App\Exception\ValidationException
     */
    public function addComment()
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
                    return json_encode([
                        'url' => '/post/' . $post->slug . '?#comments'
                    ]);

                } else {
                    return json_encode([
                        'toast' => [
                            'typeToast' => 'warning',
                            'dataToast' => [
                                'message' => 'Указанного поста не существует'
                            ]
                        ]
                    ]);
                }


            } else {
                return json_encode([
                    'toast' => [
                        'typeToast' => 'warning',
                        'dataToast' => [
                            'message' => 'Ошибка записи данных комментария'
                        ]
                    ]
                ]);
            }

        } else {
            return json_encode([
                'toast' => [
                    'typeToast' => 'warning',
                    'dataToast' => [
                        'message' => 'Ничего не пришло'
                    ]
                ]
            ]);
        }
    }
}
