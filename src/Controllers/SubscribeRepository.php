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

    /**
     * @param string $hash
     * @param string $email
     * @return mixed
     */
    public function getSubscribeByHashAndEmail(string $hash, string $email)
    {
        return Subscriber::where('email', $email)
            ->where('hash', $hash)->first();
    }

    /**
     * @param string $email
     * @param string $hash
     * @return bool|null
     */
    public function deleteSubscriber(string $email, string $hash): ?bool
    {
//        $subscriber = $this->getSubscribeByHashAndEmail($hash, $email);
//
//        if(!empty($subscriber)) {
//            return $subscriber->delete();
//        }

        if(Subscriber::where('email', $email)
            ->where('hash', $hash)
            ->delete()) {
            return true;
        }

        return false;
    }

    /**
     * @param string $email
     * @return bool|null
     */
    public function deleteSubscriberByEmail(string $email): ?bool
    {
        return Subscriber::where('email', $email)
            ->delete();
    }
}
