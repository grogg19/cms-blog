<?php
/**
 * Класс ToastsController
 */

namespace App\Controllers;

use App\Cookie\Cookie;
use App\Jsonable;
use App\Renderable;
use App\View;

use function Helpers\checkToken;

/**
 * Class ToastsController
 * @package App\Controllers
 */
class ToastsController extends Controller implements Jsonable
{

    /**
     * @var array
     */
    private $data;

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
        $this->data = [
            'toast' => [
                'typeToast' => $type,
                'dataToast' => [
                    'message' => $message
                ]
            ]
        ];
        return $this->json();
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

    /**
     * Возвращает json
     * @return string
     */
    public function json(): string
    {
        return json_encode($this->data);
    }
}
