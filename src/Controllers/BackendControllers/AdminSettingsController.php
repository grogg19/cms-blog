<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Model\SystemSetting;
use App\Validate\Validator;
use App\Repository\SystemSettingsRepository;
use App\View;

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

        if (!$this->auth->checkPermissons(['admin'])) {
            $this->toast->setToast('info', 'У вас недостаточно прав для этого действия');
            Redirect::to('/admin/account');
        };
    }

    /**
     * метод выводит шаблон с настройками CMS
     * @return Renderable
     */
    public function index(): Renderable
    {
        $settings = (new SystemSettingsRepository())->getSystemSettings('preferences');

        $dataSettings = [
            'title' => 'Настройки системы',
            'token' => generateToken(),
            'settings' => $settings,
            'parameters' => json_decode($settings->value),
        ];

        $view = 'admin.settings.settings_admin';

        return new View($view, $dataSettings);

    }

    /**
     * Сохранение настроек
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function saveSettings(): string
    {
        if (empty($this->request->post()) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $data = $this->request->post();
        unset($data['_token']);

        $rules = [
            'per_page' => 'isNaturalNumeric'
        ];

        $resultValidation = (new Validator($data, SystemSetting::class, $rules))->makeValidation();

        if (!empty($resultValidation['error'])) {
            return $this->toast->getToast('warning', $resultValidation['error']['per_page']['errorMessage']);
        }

        if ((new SystemSettingsRepository())->updateSystemSettings('preferences', $data)) {

            $this->toast->setToast('success', 'Настройки успешно сохранены');

            return json_encode(['url' => '/admin/settings']);

        } else {
            return $this->toast->getToast('warning', 'Ошибка сохранения в БД.');
        }
    }
}
