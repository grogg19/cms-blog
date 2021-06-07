<?php
/**
 * Контроллер модуля подписки
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Controllers\SubscribeRepository;
use App\Controllers\ToastsController;
use App\Model\Subscriber;
use App\Redirect;
use App\Validate\Validator;
use function Helpers\checkToken;

/**
 * Class PublicSubscribeController
 * @package App\Controllers\PublicControllers
 */
class PublicSubscribeController extends Controller
{
    /**
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function subscribe(): string
    {
        if(empty($this->request->post('emailSubscribe')) && !checkToken()) {
            return ToastsController::getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $this->request->post('emailSubscribe');

        $data = [
            'email' => $this->request->post('emailSubscribe')
        ];

        $validator = new Validator($data, Subscriber::class);
        $resultValidation = $validator->makeValidation();

        if(empty($resultValidation['error'])) {
            return (new SubscribeRepository())->createSubscriber($email);
        }
        return ToastsController::getToast('warning', $resultValidation['error']['email']['errorMessage']);

    }

    public function unsubscribe(string $email, string $hash)
    {
        return (new SubscribeRepository())->deleteSubscriber($email, $hash);
    }

    /**
     * Проверка подписки
     * @param string $email
     * @return bool
     */
    public function isSubscribed(string $email): bool
    {
        $subscriber = (new SubscribeRepository())->getSubscriber($email);
        if ($subscriber instanceof Subscriber) {
            return true;
        }
        return false;
    }

    public function unsubscribeByLink()
    {
        if(empty($this->request->get('email')) || empty($this->request->get('code'))) {
            (new ToastsController())->setToast('warning', 'Невозможно отписаться');
            Redirect::to('/');
        } else {
            (new ToastsController())->setToast('success', 'Вы успешно отписались');
            Redirect::to('/');
        }
    }
}
