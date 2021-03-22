<?php
/**
 * Класс LoginController
 */

namespace App\Controllers\BackendControllers;

use App\DI\DI;
use App\Parse\Yaml;
use App\View;
use App\Auth\Auth;
use App\Controllers\Controller;
use App\Validator\UserFormValidation;
use App\Model\User;
use App\Redirect;
use App\Cookie\Cookie;
use App\Controllers\ListenerController;

use function Helpers\checkToken;
use function Helpers\hashPassword;
use function Helpers\getCurrentDate;

class LoginController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = new Auth();
        parent::__construct();

    }

    public function form()
    {
        $fields = (new Yaml())->parseFile(APP_DIR . '/src/Model/User/user_login_fields.yaml');

        $fields['token'] = \Helpers\generateToken();

        return new View('index', ['view' => 'partials.login', 'data' => $fields]);
    }

    public function adminAuth()
    {
        $parameters = $this->request->post();

        // Если есть $parameters и токены совпадают,
        if(!empty($parameters) && checkToken()) {
            // то валидируем введеные данные с формы
            // Создаем экземпляр валидации
            $validator = new UserFormValidation();

            // Создаем свои правила валидации
            $ownRules = [
                'email' => ['required', 'email', 'between:2,255'],
                'password' => ['required', 'between:6,255', 'confirmed']
            ];

            // и проверяем данные валидатором созданными правилами $ownRules
            $resultValidateForms = $validator->validate($parameters, $ownRules);

            // если ошибок в валидации не было,
            if(!isset($resultValidateForms['error']))  {
                // то ищем юзера с парой email-пароль в бд
                $user = $this->findUser($parameters['email'], $parameters['password']);
                if($user !== false) {

                    // Если есть такой юзер, то авторизуем его и возвращаем на страницу, с которой он логинился
                    $persistCode = $this->makeUserHash($user);

                    $user->persist_code = $persistCode;
                    $user->last_login = getCurrentDate();
                    $user->save();

                    $this->auth->setAuthorized($persistCode);
                    $this->auth->setUserAttributes($user);

                    return json_encode([
                        'url' => (!empty(Cookie::get('targetUrl'))) ? Cookie::get('targetUrl') : $this->request->server('HTTP_REFERER')
                    ]);
                } else {
                    // Если пользователя нет, возвращаем сообщение, что такого пользователя нет.
                    return json_encode([
                        'error' => [
                            'login' => [
                                'field' => 'email',
                                'errorMessage' => 'Пользователь с такими данными не найден'
                            ]
                        ]
                    ]);
                }

            } else {
                return json_encode($resultValidateForms);
            }
        }

    }

    /**
     * Метод ищет пользователя по $login или $email и если находит, сверяет пароль с $password
     * и возвращает объект пользователя или FALSE при несовпадении пароля
     * @param $login
     * @param $password
     * @return bool
     */
    protected function findUser($email, $password)
    {
        // Ищем пользователя с логином $email
        $userData = User::where('email', $email)
            ->first();

        // Если такой пользователь есть, сравниваем пароль $password с паролем пользователя
        if(!empty($userData->email)) {
            if(password_verify($password, $userData->password)) {
                // Если пароль совпал, возвращается объект этого пользователя
                return $userData;
            } else {
                // Иначе возвращается FALSE
                return false;
            }
        } else {
            // Иначе возвращается FALSE
            return false;
        }
    }

    /**
     * Метод выполняет выход пользователя из сессии
     */
    public function logout() {
        $this->auth->unAuthorize();
        (new ListenerController())->ImageListener();
        Redirect::to('/'); // Редирект на главную страницу
    }

    public function makeUserHash(User $user)
    {
        return hashPassword($user->id . $user->email . $user->password);
    }

    public function login()
    {
        $this->auth = new Auth();

        if($this->auth->getHashUser() !== null)
        {


            $user = $this->auth->userByHash($this->auth->getHashUser());

            if($user !== null) {
                $hash = $this->makeUserHash($user);
                $user->persist_code = $hash;
                $user->save();
                $this->auth->setAuthorized($hash);

                $this->auth->setUserAttributes($user);
                Redirect::to('/');
            } else {
                Redirect::to('/login');
            }

        }
    }
}
