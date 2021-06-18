<?php
/**
 * Class AdminUserManagerController
 * Контроллер управления пользователями
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
use App\Repository\UserRepository;
use App\View;

use Illuminate\Pagination\LengthAwarePaginator;
use function Helpers\checkToken;
use function Helpers\generateToken;

/**
 * Class AdminUserManagerController
 * @package App\Controllers\BackendControllers
 */
class AdminUserManagerController extends AdminController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * AdminUserManagerController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();

        $user = $this->userRepository->getCurrentUser();

        $this->auth->checkSuperUser($user);

    }

    /**
     * Выводит список пользователей
     * @return View
     */
    public function listUsers(): View
    {
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;

        $users = $this->userRepository->getAllUsers('asc', $quantity);

        if($users instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $users->setPath('user-manager' . $query);
        }

        return new View('admin', [
            'view' => 'admin.users_manager.list',
            'data' => [
                'title' => 'Список пользователей',
                'users' => $users,
                'token' => generateToken(),
                'pathToAvatar' => $this->userRepository->getUserAvatarPath(),
                'roles' => $this->userRepository->getUserRoles(),
                'quantity' => $quantity,
                'currentUser' => $this->session->get('userId')
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
        if(!checkToken() || empty($this->request->post('user'))) {
            return (new ToastsController())->getToast('warning', 'Ошибка токена, обновите страницу.');
        }

        if(!empty($this->request->post('active_status'))) {
            $data['is_activated'] = ($this->request->post('active_status') === 'true') ? 1 : 0;
        }

        if(!empty($this->request->post('role'))) {
            $data['role_id'] = $this->request->post('role');
        }

        $user = $this->userRepository->getUserById((int) $this->request->post('user'));

        if($this->userRepository->updateUser($user, $data)) {
            return (new ToastsController())->getToast('success', 'Данные пользователя изменены');
        } else {
            return (new ToastsController())->getToast('warning', 'Ошибка изменений в БД');
        }
    }
}
