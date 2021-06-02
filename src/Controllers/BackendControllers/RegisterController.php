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
     * @return View|bool|string
     * @throws \App\Exception\ValidationException
     */
    public function signup()
    {
        if(!empty($this->request->post())) {
            return $this->createUser($this->request->post());
        } else {
            return $this->form();
        }
    }

    /**
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
     * @return bool|string
     * @throws \App\Exception\ValidationException
     */
    private function createUser(array $parameters): bool|string
    {
        // Если есть POST данные и токен соответствует,
        if(!empty($parameters) && checkToken()) {

            if(empty($parameters['agreement']) || $parameters['agreement'] !== 'on') {
                return ToastsController::getToast('warning', 'Необходимо согласие с пользовательским соглашением');
            }
            // то валидируем введеные данные с формы
            // Создаем экземпляр валидации
            $validator = new Validator($parameters, User::class);

            // проверяем данные валидатором
            $resultValidateForms = $validator->makeValidation();
            // если ошибок в валидации не было,
            if(!isset($resultValidateForms['error']))  {

                $persistCode  = hashPassword(generateRandomHash());
                $parameters['persist_code'] = $persistCode;
                $parameters['is_activated'] = 1;
                $parameters['role_id'] = 3;

                // то создаем юзера в бд
                $userController = new UserController();
                $user = $userController->addUser($parameters);

                // Если все сохранено, авторизируем пользователя
                if($user !== null && $user instanceof User) {

                    // Если есть такой юзер, то авторизуем его и возвращаем на страницу, с которой он логинился

                    $this->auth->setAuthorized($persistCode);
                    $this->auth->setUserAttributes($user);

                    (new ToastsController())->setToast('success', 'Пользователь успешно создан!');
                    return json_encode([
                        'url' => $_SERVER['HTTP_REFERER']
                    ]);
                } else {
                    // Если нет, то возвращаем сообщение об ошибке записи в БД.
                    return ToastsController::getToast('warning', 'Ошибка записи в БД!');
                }

            } else {
                return json_encode($resultValidateForms);
            }

        } else {
            // Если нет, то возвращаем сообщение об ошибке создания пользователя.
            return ToastsController::getToast('warning', 'Невозможно создать пользователя');
        }
    }
}
