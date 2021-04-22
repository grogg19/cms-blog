<?php
/**
 * Класс IsEmpty
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

class IsEmpty extends Validation
{
    /**
     * IsEmpty constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isEmpty($this->data);
    }

    /**
     * @param $data
     * @return bool
     */
    private function isEmpty($data): bool
    {
        if (mb_strlen(filter_var($data, FILTER_SANITIZE_STRING )) != 0 && $data != 'undefined') {
            //if ($dataField !== "" && $dataField !== 'undefined') {
            return true;
        } else {
            $this->message = "Поле должно быть заполнено";
            return false;
        }
    }
}
