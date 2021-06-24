<?php
/**
 * Класс Toast
 */

namespace App\Toasts;

use App\Cookie\Cookie;
use App\Renderable;
use App\View;

use function Helpers\checkToken;
use function Helpers\request;

/**
 * Class Toast
 * @package App\Toasts
 */
class Toast
{

    /**
     * @var \App\Request\Request
     */
    private $request;

    public function __construct()
    {
        $this->request = request();
    }

    /**
     * отрисовывает шаблон Тостов
     * @return Renderable|null
     */
    public function index(): ?Renderable
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
        return null;
    }

    /**
     * выводит JSON данные тоста для ajax
     * @param string $type
     * @param string $message
     * @return string
     */
    public function getToast(string $type = 'info', string $message = ''): string
    {
        $data = [
            'toast' => [
                'typeToast' => $type,
                'dataToast' => [
                    'message' => $message
                ]
            ]
        ];
        return json_encode($data);
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

        Cookie::set('toast', json_encode($data));
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

            $type = json_decode(Cookie::get('toast'))->typeToast;
            $message = json_decode(Cookie::get('toast'))->dataToast->message;

            $content = new View('partials.toast_main', [
                'typeToast' => $type,
                'dataToast' => $message
            ]);

            $content->render();

            $this->destroyToast();
        }
    }
}
