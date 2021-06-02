<?php
/**
 * class SubscribeRepository
 */

namespace App\Controllers;

use App\Model\Subscriber;
use function Helpers\generateRandomHash;
/**
 * Class SubscribeRepository
 * @package App\Controllers
 */
class SubscribeRepository
{
    /**
     * создание подписчика
     * @param string $email
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function createSubscriber(string $email): string
    {

        $data = [
            'email' => $email,
            'hash' => generateRandomHash()
        ];

        $subscriber = new Subscriber($data);
        if($subscriber->save()) {
            return ToastsController::getToast('success', 'Вы успешно подписались на рассылку');
        }
        return ToastsController::getToast('warning', 'Подписаться не получилось');
    }

    /**
     * @param string $email
     * @return Subscriber|null
     */
    public function getSubscriber(string $email): ?Subscriber
    {
        return Subscriber::where('email', $email)->first();
    }

    public function getSubscriberByHashAndEmail(string $hash, string $email)
    {
        return Subscriber::where('email', $email)
            ->andWhere('hash', $hash)->first();
    }

    /**
     * @param string $hash
     * @return bool|null
     */
    public function deleteSubscriberByHash(string $hash): ?bool
    {
        return Subscriber::where('hash', $hash)->delete();
    }
}
