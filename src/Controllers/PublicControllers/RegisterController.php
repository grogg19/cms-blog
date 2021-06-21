<?php
/**
 * Class RegisterController
 */

namespace App\Controllers\PublicControllers;

use App\Controllers\ToastsController;
use App\Renderable;
use App\Repository\UserRepository;
use App\Model\User;
use App\Validate\Validator;
use App\Parse\Yaml;
use App\Auth\Auth as Auth;

use function Helpers\checkToken;
use function Helpers\generateToken;
use function Helpers\hashPassword;
use function Helpers\generateRandomHash;

/**
 * Class RegisterController
 * @package App\Controllers\PublicControllers
 */
class RegisterController extends PublicController
{
    /**
     * @var Auth
     */
    protected Auth $auth;

    /**
     * RegisterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->auth = new Auth();
    }

    /**
     * Возвращает форму регистрации
     * @return Renderable
     */
    public function signUp(): Renderable
    {
        return $this->form();
    }

    /**
     * Запускает регистрацию пользователя
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function registerUser(): string
    {
        return $this->createUser($this->request->post());
    }

    /**
     * Вывод формы регистрации нового пользователя
     * @return Renderable
     */
    private function form(): Renderable
    {
        $fields = (new Yaml())->parseFile(APP_DIR . '/src/Model/User/user_fields.yaml');

        $fields['token'] = generateToken();

        $this->data = [
            'view' => 'partials.signup',
            'data' => $fields
        ];

        return $this;
    }

    /**
     * @param array $parameters
     * @return string
     * @throws \App\Exception\ValidationException
     */
    private function createUser(array $parameters): string
    {

        if (empty($parameters) || !checkToken()) {
            // Если нет post данных или токена, то возвращаем сообщение об ошибке создания пользователя.
            return (new ToastsController())->getToast('warning', 'Невозможно создать пользователя');
        }
        // Если есть POST данные и токен соответствует,

        // Проверяем согласие с пользовательским соглашением
        if(empty($parameters['agreement']) || $parameters['agreement'] !== 'on') {
            return (new ToastsController())->getToast('warning', 'Необходимо согласие с пользовательским соглашением');
        }
        // Валидируем введеные данные с формы
        // Создаем экземпляр валидации
        $validator = new Validator($parameters, User::class);

        // проверяем данные валидатором
        $resultValidateForms = $validator->makeValidation();

        // если есть ошибки валидации, возвращаем их
        if (isset($resultValidateForms['error'])) {
            $this->data = $resultValidateForms;

            return $this->json();
        }
        // если ошибок в валидации не было

        $persistCode  = hashPassword(generateRandomHash());
        $parameters['persist_code'] = $persistCode;
        $parameters['is_activated'] = 1;
        $parameters['role_id'] = 3;

        // то создаем юзера в бд
        $userRepository = new UserRepository();
        $user = $userRepository->addUser($parameters);

        // Если все сохранено, авторизируем пользователя
        if($user !== null && $user instanceof User) {

            // Если успешно сохранился, то авторизуем его и возвращаем на страницу, с которой он логинился

            $this->auth->setAuthorized($persistCode);
            $this->auth->setUserAttributes($user);

            // возвращаем сообщение об успешной регистрации
            (new ToastsController())->setToast('success', 'Пользователь успешно создан!');

            $this->data = [
                'url' => $_SERVER['HTTP_REFERER']
            ];

            return $this->json();
        }
        // Если нет, то возвращаем сообщение об ошибке записи в БД.
        return (new ToastsController())->getToast('warning', 'Ошибка записи в БД!');
    }
}
