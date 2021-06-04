<?php
/**
 * Класс ToastsController
 */
namespace App\Controllers;

use App\View;
use function Helpers\checkToken;

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
     * @return bool
     */
    private function issetToast(): bool
    {
        if(!$this->session->isStarted()){
            return false;
        } else {
            return $this->session->has('toast');
        }

    }

    /**
     * @param string $type
     * @param string $message
     */
    public function setToast(string $type = 'info', string $message = ''): void
    {
        $this->session->set('toast', [
            'typeToast' => $type,
            'dataToast' => [
                'message' => $message
            ]
        ]);
    }

    /**
     * Удаление Тоста
     */
    private function destroyToast(): void
    {
        $this->session->remove('toast');
    }


    public function checkToast(): void
    {
        if($this->issetToast()) {

            $type = $this->session->get('toast')['typeToast'];
            $message = $this->session->get('toast')['dataToast']['message'];

            $this->destroyToast();

            $content = new View('partials.toast_main', [
                'typeToast' => $type,
                'dataToast' => $message
            ]);

            $content->render();
        }
    }
}
