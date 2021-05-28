<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;

use App\View;

use function Helpers\generateToken;

class AdminSettingsController extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->auth->checkPermissons(['admin', 'content-manager']);
    }

    public function index()
    {
        $title = 'Настройки бэкенда';
        (new View('admin', [
            'view' => 'admin.settings.settings_admin',
            'data' => [
                'title' => $title,
                'token' => generateToken()
            ],
            'title' => 'Администрирование | ' . $title
        ]))->render();
    }
}
