<?php
/**
 * Class AdminUserManagerController
 * Контроллер управления пользователями
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\UserRepository;
use App\View;
use Illuminate\Pagination\LengthAwarePaginator;

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

        if (!$this->auth->checkPermissons(['admin'])) {
            $this->toast->setToast('info', 'У вас недостаточно прав для этого действия');
            Redirect::to('/admin/account');
        };

    }

    /**
     * Рендер списка пользователей
     * @return Renderable
     */
    public function listUsers(): Renderable
    {
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;

        $users = $this->userRepository->getAllUsers('asc', $quantity);

        if($users instanceof LengthAwarePaginator) {
            $query = (!empty($quantity)) ? '?quantity=' . $quantity : '';
            $users->setPath('user-manager' . $query);
        }

        $dataUsersList = [
            'title' => 'Список пользователей',
            'users' => $users,
            'token' => generateToken(),
            'pathToAvatar' => $this->userRepository->getUserAvatarPath(),
            'roles' => $this->userRepository->getUserRoles(),
            'quantity' => $quantity,
            'currentUser' => $this->data['user'],
        ];

        if($quantity !== 'all') {
            $dataUsersList['paginator'] = $users;
        }

        $this->view = 'admin.users_manager.list';
        $this->data = array_merge($this->data, $dataUsersList);

        return new View($this->view, $this->data);
    }

    /**
     * Метод меняет роль пользователя и статус
     * Возвращает сообщение в формате JSON
     * @return string
     */
    public function userChangeData(): string
    {
        if(!checkToken() || empty($this->request->post('user'))) {
            return $this->toast->getToast('warning', 'Ошибка токена, обновите страницу.');
        }

        if(!empty($this->request->post('active_status'))) {
            $data['is_activated'] = ($this->request->post('active_status') === 'true') ? 1 : 0;
        }

        if(!empty($this->request->post('role'))) {
            $data['role_id'] = $this->request->post('role');
        }

        $user = $this->userRepository->getUserById((int) $this->request->post('user'));

        if($this->userRepository->updateUser($user, $data)) {
            return $this->toast->getToast('success', 'Данные пользователя изменены');
        } else {
            return $this->toast->getToast('warning', 'Ошибка изменений в БД');
        }
    }
}
