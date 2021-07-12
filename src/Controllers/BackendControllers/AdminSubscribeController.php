<?php

namespace App\Controllers\BackendControllers;

use App\Controllers\PublicControllers\PublicSubscribeController;
use App\Repository\SubscribeRepository;
use App\Request\Request;

/**
 * Class AdminSubscribeController
 * @package App\Controllers\BackendControllers
 */
class AdminSubscribeController extends AdminController
{

    /**
     * Подписка на уведомления
     * @param Request $request
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function subscribe(Request $request): string
    {
        if ($request->post('switch') === 'false') {
            return $this->unsubscribe($request);
        }

        if ($request->post('switch') === 'true') {
            return (new PublicSubscribeController())->subscribe($request);
        }

        return '';
    }

    /**
     * Отписка от уведомлений
     * @param Request $request
     * @return string
     */
    public function unsubscribe(Request $request): string
    {
        if (empty($request->post('emailSubscribe')) || !checkToken()) {
            return $this->toast->getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $request->post('emailSubscribe');

        if ((new SubscribeRepository())->deleteSubscriberByEmail($email)) {
            return $this->toast->getToast('success', 'Вы успешно отписались от рассылки');
        }
        return $this->toast->getToast('warning', 'Отписаться в данный момент невозможно');
    }
}
