<?php
/**
 *  Class AdminAccountController
 *  Данный контроллер позволяет выводить профиль пользователя, а также управлять его реквизитами, например ФИО, пароль,
 *  аватар и тп.
 */

namespace App\Controllers\BackendControllers;

use App\Toasts\Toast;
use App\Model\User;
use App\Renderable;
use App\Uploader\Upload;
use App\Validate\Validator;
use App\Repository\UserRepository;
use App\Parse\Yaml;
use App\View;

use function Helpers\checkToken;
use function Helpers\generateToken;

/**
 * Class AdminAccountController
 * @package App\Controllers\BackendControllers
 */
class AdminAccountController extends AdminController
{
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    /**
     * Рендерит представление формы редактирования пользователя
     * @return Renderable
     */
    public function editUserProfileForm(): Renderable
    {
        if(!empty($this->session->get('userId'))) {
            $user = $this->userRepository->getUserById($this->session->get('userId'));
        }

        $data = [
            'view' => 'admin.account.edit_account',
            'data' => [
                'form' => $this->getUserAccountFields(),
                'user' => $user,
                'token' => generateToken(),
                'pathToAvatar' => $this->userRepository->getUserAvatarPath()
            ],
            'title' => 'Редактирование профиля пользователя'
        ];

        return (new View('admin', $data));
    }

    /**
     * Рендерит представление профиля пользователя
     * @return Renderable
     */
    public function getUserProfile(): Renderable
    {
        if(!empty($this->session->get('userId'))) {
            $user = $this->userRepository->getUserById($this->session->get('userId'));
        } else {

            return (new View('404'));
        }

        $data = [
            'view' => 'admin.account.view_account',
            'data' => [
                'user' => $user,
                'pathAvatar' => $this->userRepository->getUserAvatarPath(),
                'title' => 'Профиль пользователя',
                'token' => generateToken()
            ],
            'title' => 'Профиль пользователя'
        ];
        return (new View('admin', $data));
    }

    /**
     * Метод возвращает массив полей для формы пользователя в личном кабинете
     * @return array
     */
    private function getUserAccountFields(): array
    {
        return (new Yaml())->parseFile(__DIR__ . '/../../Model/User/user_profile_fields.yaml');
    }

    /**
     * Метод обновляет информацию о пользователе и сообщает о результате работы
     * @return false|string
     * @throws \App\Exception\ValidationException
     */
    public function updateUserProfile(): false|string
    {
        if(empty($this->request->post()) || !checkToken() || empty($this->session->get('userId'))) {
            //  возвращаем сообщение об ошибке записи в БД.
            return (new Toast())->getToast('warning', 'Невозможно обновить данные пользователя');
        }

        // Если есть POST данные и токен соответствует,
        $user = $this->userRepository->getUserById($this->session->get('userId'));

        $data = $this->request->post();

        // Подготовка правил для валидации
        if(!empty($data['password'])) {
            $ownRules = [
                'first_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu'],
                'last_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu'],
                'password' => ['between:6,255', 'identityWith:password_confirm'],
                'password_confirm' => ['required','identityWith:password', 'between:6,255']
            ];
        } else {
            $ownRules = [
                'first_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu'],
                'last_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu']
            ];
        }

        // Создаем экземпляр валидации
        $validator = new Validator($data, User::class, $ownRules);

        // проверяем данные валидатором
        $resultValidateForms = $validator->makeValidation();

        // если есть ошибки
        if(isset($resultValidateForms['error']))  {

            // возвращаем результат валидации в json
            return json_encode($resultValidateForms);
        }

        // если ошибок в валидации не было

        // Подготовка данных к апдейту
        $data = $this->prepareDataToUpdate();

        $uploadAvatar = $this->uploadAvatar($user); // Пробуем загрузить аватарку

        // если получили сообщение об ошибке, то выведем его в блок аватара
        if(isset($uploadAvatar->error)) {
            $error = implode(', ', $uploadAvatar->error);
            return (new Toast())->getToast('warning', $error);

        } else {
            // Если ошибок нет, то добавляем к пользователю атрибут $data['avatar']
            $data['avatar'] = ($uploadAvatar === null) ? $user->avatar : $uploadAvatar->uploadFilesData[0]->fileName;
        }

        // записываем изменения в БД
        if($this->userRepository->updateUser($user, $data)) {

            (new Toast())->setToast('success', 'Изменения успешно сохранены.');

            // Перенаправляем обратно в профиль

            return json_encode(['url' => '/admin/account/']);

            // Если не удалось сохранить изменения, выводим сообщение об этом
        } else {
            return (new Toast())->getToast('warning', 'Невозможно обновить данные пользователя');
        }
    }

    /**
     * Метод подготавливает данные формы профиля для перезаписи профиля
     * @return array
     */
    protected function prepareDataToUpdate(): array
    {
        // Очищаем post - массив от пустых элементов
        return array_diff(($this->request->post()), array(''));
    }

    /**
     *  Метод загружает аватар и возвращает результат его загрузки
     * @param User $user
     * @return object|null
     */
    protected function uploadAvatar(User $user): object|null
    {
        // Если есть файл на загрузку в массиве $_FILES
        if($this->request->files('avatar')['size'] !== 0) {

            // Создаем загрузчик и кладем в него этот файл
            $uploader = new Upload($this->request->files());

            // Получаем результат загрузки файла с его новыми реквизитами
            $resultUpload = json_decode($uploader->upload('avatars'));

            // Удаляем старый аватар если он был
            if(!empty($user->avatar) && file_exists($this->userRepository->getUserAvatarRootPath() . $user->avatar) && !isset($resultUpload->error)) {
                unlink($this->userRepository->getUserAvatarRootPath() . $user->avatar);
            }

            return $resultUpload;
        }

        return null;
    }
}
