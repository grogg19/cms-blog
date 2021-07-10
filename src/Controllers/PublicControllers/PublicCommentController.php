<?php

namespace App\Controllers\PublicControllers;

use App\Auth\Auth;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Model\Comment;
use App\Validate\Validator;

/**
 * Class PublicCommentController
 * @package App\Controllers\PublicControllers
 */
class PublicCommentController extends PublicController
{
    /**
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function addComment(): string
    {

        if ((new Auth())->checkAuthorization()['access'] === 'denied') {
            return $this->toast->getToast('warning', 'Оставлять комментарии могут только зарегистрированные пользователи');
        }

        if (empty($this->request->post()) || !checkToken()) {
            return $this->toast->getToast('warning', 'Нет входящих данных, обновите страницу');
        }

        $commentDataToSave = [
            'user_id' => $this->session->get('userId'),
            'content' => strip_tags((string) $this->request->post('commentContent')),
        ];

        $commentDataToSave['has_moderated'] = (in_array((new UserRepository())
            ->getCurrentUser()
            ->role
            ->code, ['admin', 'content-manager'])) ?  1 : 0;

        // создаем валидатор
        $validator = (new Validator($commentDataToSave, Comment::class));

        // проверяем данные валидатором
        $resultValidateForms = $validator->makeValidation();

        // если есть ошибки, возвращаем Тост с ошибкой
        if (!empty($resultValidateForms['error'])) {
            return $this->toast->getToast('warning', 'Ошибка записи данных комментария');
        }

        $comment = new Comment($commentDataToSave);
        $post = (new PostRepository())->getPostById((int) $this->request->post('postId'));

        if ($post !== null) {

            $post->comments()->save($comment);

            $this->toast->setToast('success', 'Комментарий успешно сохранён.');

            return json_encode(['url' => '/post/' . $post->slug]);

        }

        return $this->toast->getToast('warning', 'Указанного поста не существует');

    }
}
