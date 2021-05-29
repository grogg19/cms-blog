<?php
/**
 * Класс SystemSettingsController
 */

namespace App\Controllers;

use App\Model\SystemSetting;

/**
 * Class SystemSettingsController
 * @package App\Controllers\BackendControllers
 */
class SystemSettingsRepository {

    /**
     * @return SystemSetting
     */
    public function getSystemSettings(string $item): SystemSetting
    {
        return SystemSetting::where('item', $item)->first();
    }

    /**
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
