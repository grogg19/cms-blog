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
}
