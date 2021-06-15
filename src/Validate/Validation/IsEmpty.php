<?php
/**
 * Класс IsEmpty
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class IsEmpty
 * @package App\Validate\Validation
 */
class IsEmpty extends Validation
{
    /**
     * IsEmpty constructor.
     * @param string $data
     */
    public function __construct(string $data)
    {
        parent::__construct();

        $this->data = $data;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isEmpty();
    }

    /**
     * реализация валидации
     * @return bool
     */
    private function isEmpty(): bool
    {
        if (mb_strlen(filter_var($this->data, FILTER_SANITIZE_STRING )) != 0 && $this->data != 'undefined') {
            //if ($dataField !== "" && $dataField !== 'undefined') {
            return true;
        } else {
            $this->message = "Поле должно быть заполнено";
            return false;
        }
    }
}
