<?php
/**
 * Класс ToastsController
 */

namespace App\Controllers;

use App\Cookie\Cookie;
use App\View;
use function Helpers\checkToken;

/**
 * Class ToastsController
 * @package App\Controllers
 */
class ToastsController extends Controller
{
    /**
     * отрисовывает шаблон Тостов
     * @return string
     */
    public function index(): string
    {
        if(!empty($this->request->post('typeToast'))
            && !empty($this->request->post('dataToast'))
            && checkToken()
        ) {
            $content = new View('partials.toast_main', [
                'typeToast' => $this->request->post('typeToast'),
                'dataToast' => $this->request->post('dataToast')
            ]);

            $content->render();
        }
        return '';
    }

    /**
     * выводит JSON данные тоста для ajax
     * @param string $type
     * @param string $message
     * @return string
     */
    public static function getToast(string $type = 'info', string $message = ''): string
    {
        return json_encode([
            'toast' => [
                'typeToast' => $type,
                'dataToast' => [
                    'message' => $message
                ]
            ]
        ]);
    }

    /**
     * проверка наличия тоста в куках
     * @return bool
     */
    private function issetToast(): bool
    {
        if(!empty(Cookie::get('toast'))) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Устанавливает тост в куки для последующего его вывода
     * @param string $type
     * @param string $message
     */
    public function setToast(string $type = 'info', string $message = ''): void
    {
        $data = [
            'typeToast' => $type,
            'dataToast' => [
                'message' => $message
            ]
        ];

        Cookie::set('toast', serialize($data));
    }

    /**
     * Удаление Тоста
     */
    private function destroyToast(): void
    {
        Cookie::delete('toast');
    }


    /**
     * Метод проверяет наличие тоста в куках и при наличии выводит его и удаляет его из куки
     */
    public function checkToast(): void
    {
        if($this->issetToast()) {

            $type = unserialize(Cookie::get('toast'))['typeToast'];
            $message = unserialize(Cookie::get('toast'))['dataToast']['message'];

            $content = new View('partials.toast_main', [
                'typeToast' => $type,
                'dataToast' => $message
            ]);

            $content->render();

            $this->destroyToast();
        }
    }
}
