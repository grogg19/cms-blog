<?php
/**
 * Контроллер модуля подписки
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Repository\SubscribeRepository;
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
            return (new ToastsController())->getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $this->request->post('emailSubscribe');

        $data = [
            'email' => $this->request->post('emailSubscribe')
        ];

        $resultValidation = $this->getValidate($data);

        if(empty($resultValidation['error'])) {
            return (new SubscribeRepository())->createSubscriber($email);
        }
        return (new ToastsController())->getToast('warning', $resultValidation['error']['email']['errorMessage']);

    }

    private function getValidate(array $data)
    {
        $validator = new Validator($data, Subscriber::class);
        return $validator->makeValidation();
    }

    /**
     *
     * @param string $email
     * @param string $hash
     * @return bool|null
     */
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

    /**
     *  метод отписывает email от рассылки
     */
    public function unsubscribeByLink()
    {
        if(empty($this->request->get('email')) || empty($this->request->get('code'))) {
            (new ToastsController())->setToast('warning', 'Не хватает данных, чтобы отписаться');
            Redirect::to('/');
        }
        $email = (string) $this->request->get('email');
        $code = (string) $this->request->get('code');

        if($this->unsubscribe($email, $code)) {
            (new ToastsController())->setToast('success', 'Вы успешно отписались');
        } else {
            (new ToastsController())->setToast('warning', 'В данный момент невозможно отписаться');
        }
        Redirect::to('/');
    }
}
