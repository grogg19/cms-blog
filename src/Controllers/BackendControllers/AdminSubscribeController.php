<?php

namespace App\Controllers\BackendControllers;

use App\Controllers\PublicControllers\PublicSubscribeController;
use App\Controllers\SubscribeRepository;
use App\Controllers\ToastsController;
use function Helpers\checkToken;

class AdminSubscribeController extends AdminController
{

    /**
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
     * @return string
     */
    public function unsubscribe(): string
    {
        if(empty($this->request->post('emailSubscribe')) || !checkToken()) {
            return ToastsController::getToast('warning', 'Данные недействительны, обновите страницу');
        }

        $email = (string) $this->request->post('emailSubscribe');

        if((new SubscribeRepository())->deleteSubscriberByEmail($email)) {
            return ToastsController::getToast('success', 'Вы успешно отписались от рассылки');
        }
        return ToastsController::getToast('warning', 'Отписаться в данный момент невозможно');
    }
}
