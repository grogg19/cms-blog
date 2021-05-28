<?php
/**
 * Класс AdminSettingsController
 */

namespace App\Controllers\BackendControllers;


use App\View;

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
            'data' => [ 'title' => $title ],
            'title' => 'Администрирование | ' . $title
        ]))->render();
    }
}
