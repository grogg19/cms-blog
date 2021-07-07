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
    /**
     * @var array
     */
    public $elements;

    /**
     * FormRenderer constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->elements = $data;
    }

    /**
     * Генератор элементов формы
     * @param null $data
     * @return string
     */
    private function generateForms($data = null)
    {

        ob_start();
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
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    /**
     * @param object|null $data
     * @return string
     */
    public function render(object $data = null): string
    {
        return $this->generateForms($data);
    }
}
