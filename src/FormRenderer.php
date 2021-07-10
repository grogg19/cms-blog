<?php
/**
 * Класс FormRenderer
 *
 */

namespace App;

/**
 * Class FormRenderer
 * @package App
 */
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

                (new Form("forms/input",
                    [
                        'element' => [$key => $element],
                        'valueAttribute' => $valueAttribute
                    ]
                ))->render();

            }

            if($element['form'] == "textarea") {
                (new Form("forms/textarea", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                    ->render();
            }

            if($element['form'] == "editor") {
                (new Form("forms/editor", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                ->render();
            }

            if($element['form'] == "datetimepicker") {
                (new Form("forms/datetimepicker", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
                ->render();
            }

            if($element['form'] == "switch") {
                (new Form("forms/switch", ['element' => [$key => $element], 'valueAttribute' => $valueAttribute]))
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
