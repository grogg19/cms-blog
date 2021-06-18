<?php
/**
 * class SubscribeRepository
 */

namespace App\Repository;

use App\Model\Subscriber;
use Illuminate\Support\Collection;
use App\Controllers\ToastsController;

use function Helpers\generateRandomHash;
/**
 * Class SubscribeRepository
 * @package App\Controllers
 */
class SubscribeRepository extends Repository
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
            'hash' => generateRandomHash() // генерируем рандомный хэш
        ];

        $subscriber = new Subscriber($data);

        if($subscriber->save()) {
            return (new ToastsController())->getToast('success', 'Вы успешно подписались на рассылку');
        }
        return (new ToastsController())->getToast('warning', 'Подписаться не получилось');
    }

    /**
     * Возвращает подписку из бд по email
     * @param string $email
     * @return Subscriber|null
     */
    public function getSubscriber(string $email): ?Subscriber
    {
        return Subscriber::where('email', $email)->first();
    }

    /**
     * Возвращает подписку из бд по email и hash
     * @param string $hash
     * @param string $email
     * @return Subscriber|null
     */
    public function getSubscribeByHashAndEmail(string $hash, string $email): ?Subscriber
    {
        return Subscriber::where('email', $email)
            ->where('hash', $hash)->first();
    }

    /**
     * удаляет подписку из бд по email и hash
     * @param string $email
     * @param string $hash
     * @return bool
     */
    public function deleteSubscriber(string $email, string $hash): bool
    {
        if(Subscriber::where('email', $email)
            ->where('hash', $hash)
            ->delete()) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает подписку по email
     * @param string $email
     * @return bool|null
     */
    public function deleteSubscriberByEmail(string $email): ?bool
    {
        return Subscriber::where('email', $email)
            ->delete();
    }

    /**
     * Возвращает коллекцию подписок из бд
     * @return Collection
     */
    public function getAllSubscribers(): Collection
    {
        return Subscriber::all();
    }
}
