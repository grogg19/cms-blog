<?php
/**
 * Class AdminUserManagerController
 * Контроллер управления пользователями
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
use App\Controllers\UserController;
use App\View;

use function Helpers\checkToken;
use function Helpers\generateToken;

class AdminUserManagerController extends AdminController
{
    /**
     * @var UserController
     */
    private UserController $userController;

    public function __construct()
    {
        parent::__construct();

        $this->userController = new UserController();

        $user = $this->userController->getCurrentUser();

        $this->auth->checkSuperUser($user);

    }

    /**
     * @return View
     */
    public function index(): View
    {
        return new View('admin', [
            'view' => 'admin.users_manager.list',
            'data' => [
                'title' => 'Список пользователей',
                'users' => $this->userController->getAllUsers()->except(['is_superuser' => 1]),
                'token' => generateToken(),
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
    public function userChangeData(): string
    {
        if(checkToken() && !empty($this->request->post('user'))) {

            if(!empty($this->request->post('active_status'))) {
                $data['is_activated'] = ($this->request->post('active_status') === 'true') ? 1 : 0;
            }

            if(!empty($this->request->post('role'))) {
                $data['role_id'] = $this->request->post('role');
            }

            $user = (new UserController())->getUserById((int) $this->request->post('user'));

            if($this->userController->updateUser($user, $data)) {
                return ToastsController::getToast('success', 'Данные пользователя изменены');
            } else {
                return ToastsController::getToast('warning', 'Ошибка изменений в БД');
            }
        } else {
            return ToastsController::getToast('warning', 'Ошибка токена, обновите страницу.');
        }
    }
}
