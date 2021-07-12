<?php
/**
 * Class AdminUserManagerController
 * Контроллер управления пользователями
 */

namespace App\Controllers\BackendControllers;

use App\Redirect;
use App\Renderable;
use App\Repository\UserRepository;
use App\Request\Request;
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
     * @param Request $request
     * @return Renderable
     */
    public function listUsers(Request $request): Renderable
    {
        $quantity = (!empty($_GET['quantity'])) ? filter_var($_GET['quantity'], FILTER_SANITIZE_STRING) : 20;
        $page = empty($request->get('page')) ? 1 : $request->get('page');

        $users = $this->userRepository->getAllUsers('asc', $quantity, $page);

        if ($users instanceof LengthAwarePaginator) {
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
            'currentUser' => $this->userRepository->getCurrentUser(),
        ];

        if ($quantity !== 'all') {
            $dataUsersList['paginator'] = $users;
        }

        return new View('admin.users_manager.list', $dataUsersList);
    }

    /**
     * Метод меняет роль пользователя и статус
     * Возвращает сообщение в формате JSON
     * @return string
     */
    public function userChangeData(Request $request): string
    {
        if (!checkToken() || empty($request->post('user'))) {
            return $this->toast->getToast('warning', 'Ошибка токена, обновите страницу.');
        }

        if (!empty($request->post('active_status'))) {
            $data['is_activated'] = ($request->post('active_status') === 'true') ? 1 : 0;
        }

        if (!empty($request->post('role'))) {
            $data['role_id'] = $request->post('role');
        }

        $user = $this->userRepository->getUserById((int) $request->post('user'));

        if ($this->userRepository->updateUser($user, $data)) {
            return $this->toast->getToast('success', 'Данные пользователя изменены');
        } else {
            return $this->toast->getToast('warning', 'Ошибка изменений в БД');
        }
    }
}
