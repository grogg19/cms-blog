<?php
/**
 * Class AdminUserManagerController
 * Контроллер управления пользователями
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\UserController;
use App\Redirect;
use App\View;
use function Helpers\checkToken;
use function Helpers\printArray;

class AdminUserManagerController extends AdminController
{
    private $userController;

    public function __construct()
    {
        parent::__construct();

        $this->userController = new UserController();

        $user = $this->userController->getCurrentUser();

        if($user->role->code !== 'admin' &&  $user->is_superuser !== 1) {

            Redirect::to('/admin/account');
        }
    }

    public function index()
    {
        return new View('admin', [
            'view' => 'admin.users_manager.list',
            'data' => [
                'title' => 'Список пользователей',
                'users' => $this->userController->getAllUsers(),
                'token' => \Helpers\generateToken(),
                'pathToAvatar' => $this->userController->getUserAvatarPath(),
                'roles' => $this->userController->getUserRoles()
            ],
            'title' => 'Редактирование профиля пользователя'
        ]);
    }

    /**
     * Метод меняет роль пользователя и статус
     * Возвращает сообщение в формате JSON
     * @return string
     */

    public function userChangeData()
    {
        if(checkToken() && !empty($this->request->post('user'))) {

            if(!empty($this->request->post('active_status'))) {
                $data['is_activated'] = ($this->request->post('active_status') === 'true') ? 1 : 0;
            }

            if(!empty($this->request->post('role'))) {
                $data['role_id'] = $this->request->post('role');
            }

            if($this->userController->updateUser( (int) $this->request->post('user'), $data)) {
                return json_encode([
                    'message' => 'success'
                ]);
            } else {
                return json_encode([
                    'message' => 'Ошибка изменений в БД'
                ]);
            }
        } else {
            return json_encode([
                'message' => 'Ошибка токена'
            ]);
        }
    }




}