<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\UserRepository;
use App\Model\SystemSetting;
use App\Validate\Validator;
use App\Repository\SystemSettingsRepository;
use App\View;

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

        if (!$this->auth->checkSuperUser($user)) {
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
        $title = 'Настройки системы';
        $settings = (new SystemSettingsRepository())->getSystemSettings('preferences');

        $data = [
            'view' => 'admin.settings.settings_admin',
            'data' => [
                'title' => $title,
                'token' => generateToken(),
                'settings' => $settings,
                'parameters' => json_decode($settings->value)
            ],
            'title' => 'Администрирование | ' . $title
        ];

        return (new View('admin', $data));

    }

    /**
     * Сохранение настроек
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function saveSettings(): string
    {
        if(empty($this->request->post()) || !checkToken()) {
            return $this->toast->getToast('warning', 'Недостоверные данные, обновите страницу');
        }

        $data = $this->request->post();
        unset($data['_token']);

        $rules = [
            'per_page' => 'isNaturalNumeric'
        ];

        $resultValidation = (new Validator($data, SystemSetting::class, $rules))->makeValidation();

        if(!empty($resultValidation['error'])) {
            return $this->toast->getToast('warning', $resultValidation['error']['per_page']['errorMessage']);
        }

        if((new SystemSettingsRepository())->updateSystemSettings('preferences', $data)) {

            $this->toast->setToast('success', 'Настройки успешно сохранены');

            return json_encode(['url' => '/admin/settings']);

        } else {
            return $this->toast->getToast('warning', 'Ошибка сохранения в БД.');
        }
    }
}
