<?php
/**
 * Class RegisterController
 */

namespace App\Controllers\BackendControllers;

use App\Controllers\UserController;
use App\Validator\UserFormValidation;
use App\Parse\Yaml;
use App\Auth\Auth as Auth;
use App\View;
use App\Cookie\Cookie;
use App\Controllers\Controller;

use function Helpers\checkToken;
use function Helpers\hashPassword;
use function Helpers\generateRandomHash;
use function Helpers\generateToken;

class RegisterController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        parent::__construct();

    }

    /**
     *
     */
    public function signup()
    {
        if(!empty($this->request->post())) {
            return $this->createUser($this->request->post());
        } else {
            return $this->form();
        }
    }

    private function form()
    {
        $fields = (new Yaml())->parseFile(APP_DIR . '/src/Model/User/user_fields.yaml');

        $fields['token'] = \Helpers\generateToken();

        return new View('index', ['view' => 'partials.signup', 'data' => $fields]);
    }

    private function createUser(array $parameters)
    {

        // Если есть POST данные и токен соответствует,
        if(!empty($parameters) && checkToken()) {
            // то валидируем введеные данные с формы
            // Создаем экземпляр валидации
            $validator = new UserFormValidation();

            // проверяем данные валидатором
            $resultValidateForms = $validator->validate($parameters);

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
                if($user !== false) {

                    // Если есть такой юзер, то авторизуем его и возвращаем на страницу, с которой он логинился

                    $this->auth->setAuthorized($persistCode);
                    $this->auth->setUserAttributes($user);

                    return json_encode([
                        'url' => $_SERVER['HTTP_REFERER']
                    ]);
                } else {
                    // Если нет, то возвращаем сообщение об ошибке записи в БД.
                    return json_encode([
                        'error' => [
                            'auth' => [
                                'field' => 'email',
                                'errorMessage' => 'Ошибка записи в БД'
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
                    'auth' => [
                        'field' => 'email',
                        'errorMessage' => 'Невозможно создать пользователя'
                    ]
                ]
            ]);
        }
    }
}