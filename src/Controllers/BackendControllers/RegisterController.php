<?php
/**
 * Class RegisterController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\ToastsController;
use App\Controllers\UserController;
use App\Model\User;
use App\Validate\Validator;
use App\Parse\Yaml;
use App\Auth\Auth as Auth;
use App\View;
use App\Controllers\Controller;

use function Helpers\checkToken;
use function Helpers\generateToken;
use function Helpers\hashPassword;
use function Helpers\generateRandomHash;

/**
 * Class RegisterController
 * @package App\Controllers\BackendControllers
 */
class RegisterController extends Controller
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
        $this->auth = new Auth();
        parent::__construct();

    }

    /**
     * Возвращает форму регистрации
     * @return View
     */
    public function signUp(): View
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
     * @return View
     */
    private function form(): View
    {
        $fields = (new Yaml())->parseFile(APP_DIR . '/src/Model/User/user_fields.yaml');

        $fields['token'] = generateToken();

        return new View('index', ['view' => 'partials.signup', 'data' => $fields]);
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
            return ToastsController::getToast('warning', 'Невозможно создать пользователя');
        }
        // Если есть POST данные и токен соответствует,

        // Проверяем согласие с пользовательским соглашением
        if(empty($parameters['agreement']) || $parameters['agreement'] !== 'on') {
            return ToastsController::getToast('warning', 'Необходимо согласие с пользовательским соглашением');
        }
        // Валидируем введеные данные с формы
        // Создаем экземпляр валидации
        $validator = new Validator($parameters, User::class);

        // проверяем данные валидатором
        $resultValidateForms = $validator->makeValidation();

        // если есть ошибки валидации, возвращаем их
        if (isset($resultValidateForms['error'])) {
            return json_encode($resultValidateForms);
        }
        // если ошибок в валидации не было

        $persistCode  = hashPassword(generateRandomHash());
        $parameters['persist_code'] = $persistCode;
        $parameters['is_activated'] = 1;
        $parameters['role_id'] = 3;

        // то создаем юзера в бд
        $userController = new UserController();
        $user = $userController->addUser($parameters);

        // Если все сохранено, авторизируем пользователя
        if($user !== null && $user instanceof User) {

            // Если успешно сохранился, то авторизуем его и возвращаем на страницу, с которой он логинился

            $this->auth->setAuthorized($persistCode);
            $this->auth->setUserAttributes($user);

            // возвращаем сообщение об успешной регистрации
            (new ToastsController())->setToast('success', 'Пользователь успешно создан!');

            return json_encode([
                'url' => $_SERVER['HTTP_REFERER']
            ]);
        }
        // Если нет, то возвращаем сообщение об ошибке записи в БД.
        return ToastsController::getToast('warning', 'Ошибка записи в БД!');
    }
}
