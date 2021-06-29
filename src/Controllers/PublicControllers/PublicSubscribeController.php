<?php
/**
 * Контроллер модуля подписки
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Repository\SubscribeRepository;
use App\Model\Subscriber;
use App\Redirect;
use App\Validate\Validator;

/**
 * Class PublicSubscribeController
 * @package App\Controllers\PublicControllers
 */
class PublicSubscribeController extends Controller
{
    /**
     * @var SubscribeRepository
     */
    private $subscribeRepository;

    /**
     * PublicSubscribeController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->subscribeRepository = new SubscribeRepository();
    }

    /**
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function subscribe(): string
    {
        if(empty($this->request->post('emailSubscribe')) && !checkToken()) {
            return $this->toast->getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $this->request->post('emailSubscribe');

        $data = [
            'email' => $this->request->post('emailSubscribe')
        ];

        // Проверка введеного email
        $resultValidation = $this->getValidate($data);

        if(empty($resultValidation['error'])) {

            $subscriber = $this->subscribeRepository->createSubscriber($email);
            $subscriber->save();

            return $this->toast->getToast('success', 'Вы успешно подписались на рассылку');
        }

        return $this->toast->getToast('warning', $resultValidation['error']['email']['errorMessage']);
    }

    /**
     * @param array $data
     * @return array
     * @throws \App\Exception\ValidationException
     */
    private function getValidate(array $data): array
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
    public function unsubscribe(string $email, string $hash): ?bool
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
            $this->toast->setToast('warning', 'Не хватает данных, чтобы отписаться');
            Redirect::to('/');
        }
        $email = (string) $this->request->get('email');
        $code = (string) $this->request->get('code');

        if($this->unsubscribe($email, $code)) {
            $this->toast->setToast('success', 'Вы успешно отписались');
        } else {
            $this->toast->setToast('warning', 'В данный момент невозможно отписаться');
        }
        Redirect::to('/');
    }
}
