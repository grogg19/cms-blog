<?php
/**
 *  Хелпер вывода
 */

use App\Config;
use App\Request\Request;
use App\Cookie\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Функция выводит в контейнере <pre> структурированную информацию о параметре $data
 * @param $data
 */
function printArray($data)
{
    echo "<pre style='  color: orange;  background-color: #000;'>";
    print_r($data);
    echo "</pre>";
}

/**
 * Функция возращает значение из массива $array с ключом $key вида "key1.key2.***.value"
 * @param array $array
 * @param $key
 * @param null $default
 * @return array|mixed|null
 */
function array_get(array $array, $key, $default = null )
{
    //  Текущий уровень
    $currentLevel =& $array;
    //  Разбиваем $key по точке на массив
    $levels = explode('.', $key);

    // Ищем в массиве $levels ключ, совпадающий с ключом в массиве $currentLevel
    foreach ($levels as $key) {
        // Если такой ключ есть и $currentLevel[$key] является массивом
        if (array_key_exists($key, $currentLevel) && is_array($currentLevel[$key])) {
            // $currentLevel становится ссылкой на $currentLevel[$key]
            $currentLevel =& $currentLevel[$key];
        } else {
            // Иначе, возвращает значение $currentLevel[$key] или значение по дефолту
            return ((empty($currentLevel[$key])) ? $default : $currentLevel[$key]);
        }
    }
    // возвращает значение $currentLevel или значение по дефолту
    return ((empty($currentLevel)) ? $default : $currentLevel);
}

/**
 * Метод возвращает массив из элементов строки REQUEST_URI
 * @return array
 */
function parseRequestUri()
{
    $url = parse_url($_SERVER['REQUEST_URI']);
    return explode("/", trim($url['path'],'/'));
}

/**
 * Функция возвращает дату в формате d.m.Y h:i из параметра $date
 * @param $date
 * @return string
 */
function getDateTime($date) : string {
    return date('d.m.Y H:i', strtotime($date));
}

/**
 * Функция возвращает дату в формате Y.m.d  h:i:s из параметра $date
 * @param $date
 * @return string
 */
function getDateTimeForDb($date) : string {
    return date('Y.m.d  H:i:s', strtotime($date));
}

/**
 * Функция возвращает текущую дату
 * @param string $format
 * @return string
 */
function getCurrentDate($format = 'Y-m-d H:i:s'): string {
    return date($format);
}

/**
 * Функция генерирует и возвращает Токен
 * @return string
 */
function generateToken() {

    // Если  токена не существует, то
        if(empty(Cookie::get('_token'))) {
            // генерируем токен и записываем его в куки
            $token = hash('sha1', uniqid(rand()));
            Cookie::set('_token', $token, 3600);
            // возвращаем токен
            return $token;
        } else {
            // возвращаем токен
            return Cookie::get('_token');
        }
}

/**
 * Функция сверяет полученный токен с токеном из куки и возвращает true при совпадении иначе false
 * @return bool
 */
function checkToken(): bool
{
    if (!empty(request()->post('_token'))) {
        return Cookie::get('_token') === request()->post('_token');
    }
    return false;
}

/**
 * Функция хеширует пароль
 * @param $password
 * @return string
 */
function hashPassword($password): string {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Функция верифицирует пароль
 * @param $password
 * @return bool
 */
function checkPassword($password): bool {
    return password_verify($password , hashPassword($password));
}

function generateRandomHash(int $length = 64) {
    $arraySymbols = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = []; // Объявляем массив $pass
    $alphaLength = strlen($arraySymbols) - 1;
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $arraySymbols[$n];
    }
    return implode($pass); //turn the array into a string
}

function request(){
    return new Request();
}

function session()
{
    return new Session();
}

/**
 * @param string $data
 * @return string
 */
function cleanJSTags(string $data): string {
    return preg_replace('#<script[^>]*>.*?</script>#is', '', $data);
}

/**
 * путь к каталогу с изображениями
 * @return string
 */
function getImagesWebPath() {
    $configImages = Config::getInstance()->getConfig('images');
    return $configImages['pathToUpload'] . DIRECTORY_SEPARATOR;
}

/**
 * Элементы формы "количество элементов на странице"
 * @return array
 */
function quantityElements(): array {
    return Config::getInstance()->getConfig('cms')['dropdown']; // список вариантов количества элементов
}
