<?php
/**
 * Класс LoginController
 */

namespace App\Controllers\PublicControllers;

use App\Image\ImageManager;
use App\Renderable;
use App\Repository\UserRepository;
use App\Parse\Yaml;
use App\Validate\Validator;
use App\Auth\Auth;
use App\Model\User;
use App\Redirect;
use App\Cookie\Cookie;
use App\View;

use function Helpers\checkToken;
use function Helpers\generateToken;
use function Helpers\hashPassword;
use function Helpers\getCurrentDate;

/**
 * Class LoginController
 * @package App\Controllers\PublicControllers
 */
class LoginController extends PublicController
{

    /**
     * вывод формы для авторизации
     * @return Renderable
     */
    public function form(): Renderable
    {
        if(session_status() == 2) {
            Redirect::to('/admin/blog/posts');
        }

        $fields = (new Yaml())->parseFile(APP_DIR . '/src/Model/User/user_login_fields.yaml');

        $fields['token'] = generateToken();

        $data = [
            'view' => 'partials.login',
            'data' => $fields
        ];
        return (new View('index', $data));
    }

    /**
     * Авторизация в админке
     * @return string
     * @throws \App\Exception\ValidationException
     */
    public function adminAuth(): string
    {
        if(empty($this->request->post()) || !checkToken()) {
            return $this->toast->getToast('warning', 'Нет хватает данных для входа, обновите страницу!');
        }

        // Если есть данные в request и токены совпадают,
        // то валидируем введеные данные с формы

        // Создаем свои правила валидации
        $ownRules = [
            'email' => ['required', 'email'],
            'password' => ['required', 'between:6,255']
        ];

        // Создаем валидатор
        $validator = new Validator($this->request->post(), User::class, $ownRules);
        // и проверяем данные валидатором созданными правилами $ownRules
        $resultValidateForms = $validator->makeValidation();

        if(isset($resultValidateForms['error'])) {

            return json_encode($resultValidateForms);
        }

        // если ошибок в валидации не было,
        // то ищем юзера с парой email-пароль в бд
        $user = (new UserRepository())->findUser($this->request->post('email'), $this->request->post('password'));

        if($user === null) {
            // Если пользователя нет, возвращаем сообщение, что такого пользователя нет.
            return $this->toast->getToast('warning', 'Пользователь с такими данными не найден!');
        }

        $auth = new Auth();
        if(!$auth->isActivated($user)) {
            return $this->toast->getToast('warning', 'Ваша учетная запись деактивирована');
        }
        // Если есть такой юзер, то авторизуем его и возвращаем на страницу, с которой он логинился
        $persistCode = $this->makeUserHash($user);

        $user->persist_code = $persistCode;
        $user->last_login = getCurrentDate();
        $user->save();

        $auth->setAuthorized($persistCode);
        $auth->setUserAttributes($user);

        $this->toast->setToast('success', 'Вы успешно вошли в систему управления.');

        return json_encode([
            'url' => (!empty(Cookie::get('targetUrl'))) ? Cookie::get('targetUrl') : $this->request->server('HTTP_REFERER')
        ]);
    }

    /**
     * Метод выполняет выход пользователя из сессии
     */
    public function logout(): void
    {
        $auth = new Auth();
        $auth->unAuthorize();
        (new ImageManager())->checkImageUploadActuality(); // удаляем несохраненные изображения
        Redirect::to('/'); // Редирект на главную страницу
    }

    /**
     * создание хэша пользователя
     * @param User $user
     * @return string
     */
    public function makeUserHash(User $user): string
    {
        return hashPassword($user->id . $user->email . $user->password);
    }
}
