<?php
/**
 *  Class AdminAccountController
 *  Данный контроллер позволяет выводить профиль пользователя, а также управлять его реквизитами, например ФИО, пароль,
 *  аватар и тп.
 */

namespace App\Controllers\BackendControllers;

use App\Config;
use App\Controllers\BackendControllers\AdminController;
use App\Model\User;
use App\Uploader\Upload;
use App\Validate\Validator;
use App\Validator\UserFormValidation;
use App\Controllers\UserController;
use App\Redirect;
use App\View;
use App\Parse\Yaml;

use function Helpers\checkToken;

class AdminAccountController extends AdminController
{
    private $userController;

    public function __construct()
    {
        parent::__construct();
        $this->userController = new UserController();
    }

    /**
     * Возвращает представление формы редактирования пользователя
     * @return View
     */
    public function editUserProfileForm(): View
    {
        if(!empty($this->session->get('userId'))) {
            $user = $this->userController->getUserById($this->session->get('userId'));
        }

        return new View('admin', [
            'view' => 'admin/account/edit_account',
            'data' => [
                'form' => $this->getUserAccountFields(),
                'user' => $user,
                'token' => \Helpers\generateToken(),
                'pathToAvatar' => $this->userController->getUserAvatarPath()
            ],
            'title' => 'Редактирование профиля пользователя'
        ]);
    }

    /**
     * Возвращает представление профиля пользователя
     * @return View
     */
    public function getUserProfile(): View
    {
        if(!empty($this->session->get('userId'))) {
            $user = (new UserController())->getUserById($this->session->get('userId'));
        } else {
            return new View('404');
        }

        return new View('admin', [
            'view' => 'admin/account/view_account',
            'data' => [
                'user' => $user,
                'pathAvatar' => $this->userController->getUserAvatarPath(),
                'title' => 'Профиль пользователя'

            ],
            'title' => 'Профиль пользователя'
        ]);
    }

    /**
     * Метод возвращает массив полей для формы пользователя в личном кабинете
     * @return array
     */
    private function getUserAccountFields()
    {
        return (new Yaml())->parseFile(__DIR__ . '/../../Model/User/user_profile_fields.yaml');
    }

    /**
     * Метод возвращает локальный путь к файлу с аватаром
     * @return string
     */
    private function getLocalAvatarPath()
    {
        $path = Config::getInstance()->getConfig('avatars')['pathToUpload'];

        return APP_DIR . $path . DIRECTORY_SEPARATOR ;
    }

    /**
     * Метод обновляет информацию о пользователе и сообщает о результате работы
     * @return string
     */
    public function updateUserProfile()
    {
        // Если есть POST данные и токен соответствует,
        if(!empty($this->request->post()) && checkToken() && !empty($this->session->get('userId'))) {

            $userController = new UserController();
            $user = $userController->getUserById($this->session->get('userId'));

            // Подготовка данных к апдейту
            $data = $this->prepareDataToUpdate($user);

            // Подготовка правил для валидации
            if(!empty($data['password'])) {
                $ownRules = [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => ['required', 'between:6,255', 'email', 'unique:users'],
                    'password' => ['between:6,255', 'confirmed'],
                    'password_confirmation' => ['required_with:password', 'between:6,255']
                ];
            } else {
                $ownRules = [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'email' => ['between:6,255', 'email', 'unique:users'],
                ];
            }

            // Создаем экземпляр валидации
            $validator = new Validator($data, User::class, $ownRules);
            // проверяем данные валидатором
            $resultValidateForms = $validator->makeValidation();

            // если ошибок в валидации не было,
            if(!isset($resultValidateForms['error']))  {

                $uploadAvatar = $this->uploadAvatar($user); // Пробуем загрузить автарку

                if(isset($uploadAvatar->error)) { // если получили сообщение об ошибке, то выведем его в блок аватара
                    return json_encode([
                        'error' => [
                            'save' => [
                                'field' => 'avatar',
                                'errorMessage' => $uploadAvatar->error
                            ]
                        ]
                    ]);
                } else {
                    // Если ошибок нет, то добавляем к пользователю атрибут $data['avatar']
                    $data['avatar'] = ($uploadAvatar === null) ? $user->avatar : $uploadAvatar->uploadFilesData[0]->fileName;
                }

                // записываем изменения в БД
                if($userController->updateUser($this->session->get('userId'), $data)) {

                    return json_encode([
                        'url' => '/admin/account/'
                    ]);

                    // Если не удалось сохранить изменения, выводим сообщение об этом
                } else {
                    return json_encode([
                        'error' => [
                            'save' => [
                                'field' => 'email',
                                'errorMessage' => 'Невозможно обновить данные пользователя'
                            ]
                        ]
                    ]);
                }

            } else {
                return json_encode($resultValidateForms);
            }

        } else {
            // Если нет, то возвращаем сообщение об ошибке записи в БД.
            return json_encode([
                'error' => [
                    'save' => [
                        'field' => 'email',
                        'errorMessage' => 'Невозможно обновить данные пользователя'
                    ]
                ]
            ]);
        }
    }

    /**
     * Метод подготавливает данные формы профиля для перезаписи профиля
     * @param User $user
     * @return array
     */
    protected function prepareDataToUpdate(User $user): array
    {
        // Очищаем post - массив от пустых элементов
        $data = array_diff(($this->request->post()), array(''));

        // Если значения из формы совпадают с данными пользователя, то исключаем их из массива
        foreach ($data as $key => $value) {
            if($data[$key] === $user->$key ) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     *  Метод загружает аватар и возвращает результат его загрузки
     * @param User $user
     * @return mixed|null
     */
    protected function uploadAvatar(User $user)
    {
        // Если есть файл на загрузку в массиве $_FILES
        if($this->request->files('avatar')['size'] !== 0) {

            // Создаем загрузчик и кладем в него этот файл
            $uploader = new Upload($this->request->files());

            // Получаем результат загрузки файла с его новыми реквизитами
            $resultUpload = json_decode($uploader->upload('avatars'));

            // Удаляем старый аватар если он был
            if(!empty($user->avatar) && file_exists($this->getLocalAvatarPath() . $user->avatar) && !isset($resultUpload->error)) {
                unlink($this->getLocalAvatarPath() . $user->avatar);
            }

            return $resultUpload;
        }
        return null;
    }

}
