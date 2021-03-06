<?php
/**
 * контроллер для использования настроек блога в паблике
 */
namespace App\Controllers\PublicControllers;

use App\Controllers\Controller;
use App\Repository\SystemSettingsRepository;

/**
 * Class PublicSettingsController
 * @package App\Controllers\PublicControllers
 */
class PublicSettingsController extends Controller
{
    /**
     * возвращает объект настроек
     * @param string $item
     * @return object
     */
    public function getPreferencesByName(string $item): object
    {
        $preference = (new SystemSettingsRepository())->getSystemSettings($item);
        return json_decode($preference->value);
    }
}
