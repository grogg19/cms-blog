<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
use App\Repository\UserRepository;
use App\Model\SystemSetting;
use App\Validate\Validator;
use App\View;
use App\Repository\SystemSettingsRepository;
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

        $user = (new UserRepository())->getCurrentUser();

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
     * Сохранение настроек
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function saveSettings(): string
    {
        if(empty($this->request->post()) || !checkToken()) {
            return (new ToastsController())->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $data = $this->request->post();
        unset($data['_token']);

        $rules = [
            'per_page' => 'isNaturalNumeric'
        ];

        $resultValidation = (new Validator($data, SystemSetting::class, $rules))->makeValidation();

        if(!empty($resultValidation['error'])) {
            return (new ToastsController())->getToast('warning', $resultValidation['error']['per_page']['errorMessage']);
        }

        if((new SystemSettingsRepository())->updateSystemSettings('preferences', $data)) {

            (new ToastsController())->setToast('success', 'Настройки успешно сохранены');

            return json_encode([
                'url' => '/admin/settings'
            ]);

        } else {
            return (new ToastsController())->getToast('warning', 'Ошибка сохранения в БД.');
        }
    }

}
