<?php
/**
 * Класс FormRenderer
 *
 */

namespace App;

use App\Form\Input;
use App\Form\Textarea;
use App\Form\Editor;

class FormRenderer
{
    public $elements;

    /**
     * FormRenderer constructor.
     * @param $data
     */
    public function __construct(array $data)
    {
        $this->elements = $data;
    }

    /**
     * Генератор элементов формы
     * @param null $data
     */
    private function generateForms($data = null)
    {

        foreach ($this->elements as $key => $element) {

            $valueAttribute = (!empty($data)) ?  $data->$key : '';

            if($element['form'] == "input") {

                (new Input("forms/input",
                    [
                        'element' => [$key => $element],
                        'valueAttribute' => $valueAttribute
                    ]
                ))->render();

            }

            if($element['form'] == "textarea") {
                (new Textarea("forms/textarea", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                    ->render();
            }

            if($element['form'] == "editor") {
                (new Editor("forms/editor", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                ->render();
            }

            if($element['form'] == "datetimepicker") {
                (new Input("forms/datetimepicker", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                ->render();
            }

            if($element['form'] == "switch") {
                (new Input("forms/switch", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                    ->render();
            }
        }
    }
    /**
     * @param string $data
     */
    public function render($data = '')
    {
        $this->generateForms($data);
    }
}
