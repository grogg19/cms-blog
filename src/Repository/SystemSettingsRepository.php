<?php
/**
 * Класс SystemSettingsController
 */

namespace App\Repository;

use App\Model\SystemSetting;

/**
 * Class SystemSettingsController
 * @package App\Controllers\BackendControllers
 */
class SystemSettingsRepository extends Repository
{

    /**
     * возвращает настройку по идентификатору $item
     * @param string $item
     * @return SystemSetting
     */
    public function getSystemSettings(string $item): SystemSetting
    {
        return SystemSetting::where('item', $item)->first();
    }

    /**
     * Обновляет настройки SystemSetting в бд
     * @param string $item
     * @param array $data
     * @return bool
     */
    public function updateSystemSettings(string $item, array $data): bool
    {
        $dataToUpdate = ['value' => json_encode($data)];

        return $this->getSystemSettings($item)->update($dataToUpdate);
    }
}
