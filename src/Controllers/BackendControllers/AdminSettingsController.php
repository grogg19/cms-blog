<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
use App\Controllers\UserController;
use App\View;
use App\Controllers\SystemSettingsRepository;
use function Helpers\checkToken;
use function Helpers\generateToken;

/**
 * Class AdminSettingsController
 * @package App\Controllers\BackendControllers
 */
class AdminSettingsController extends AdminController
{
    /**
     * AdminSettingsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $user = (new UserController())->getCurrentUser();

        $this->auth->checkSuperUser($user);
    }

    /**
     * метод выводит шаблон с настройками CMS
     */
    public function index()
    {
        $title = 'Настройки системы';
        $settings = (new SystemSettingsRepository())->getSystemSettings('preferences');

        (new View('admin', [
            'view' => 'admin.settings.settings_admin',
            'data' => [
                'title' => $title,
                'token' => generateToken(),
                'settings' => $settings,
                'parameters' => json_decode($settings->value)
            ],
            'title' => 'Администрирование | ' . $title
        ]))->render();
    }

    /**
     * @return string
     */
    public function saveSettings(): string
    {
        if(empty($this->request->post()) || !checkToken()) {
            return ToastsController::getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $data = $this->request->post();
        unset($data['_token']);
        if((new SystemSettingsRepository())->updateSystemSettings('preferences', $data)) {

            (new ToastsController())->setToast('success', 'Настройки успешно сохранены');

            return json_encode([
                'url' => '/admin/settings'
            ]);
        } else {
            return ToastsController::getToast('warning', 'Ошибка сохранения в БД.');
        }
    }
}
