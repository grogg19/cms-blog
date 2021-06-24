<?php

namespace App\Controllers\BackendControllers;

use App\Controllers\PublicControllers\PublicSubscribeController;
use App\Repository\SubscribeRepository;
use function Helpers\checkToken;

/**
 * Class AdminSubscribeController
 * @package App\Controllers\BackendControllers
 */
class AdminSubscribeController extends AdminController
{

    /**
     * Подписка на уведомления
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function subscribe(): string
    {
        if($this->request->post('switch') === 'false') {
            return $this->unsubscribe();
        }

        if($this->request->post('switch') === 'true') {
            return (new PublicSubscribeController())->subscribe();
        }

        return '';
    }

    /**
     * Отписка от уведомлений
     * @return string
     */
    public function unsubscribe(): string
    {
        if(empty($this->request->post('emailSubscribe')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $this->request->post('emailSubscribe');

        if((new SubscribeRepository())->deleteSubscriberByEmail($email)) {
            return $this->toast->getToast('success', 'Вы успешно отписались от рассылки');
        }
        return $this->toast->getToast('warning', 'Отписаться в данный момент невозможно');
    }
}
