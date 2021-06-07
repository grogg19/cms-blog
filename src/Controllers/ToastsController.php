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
