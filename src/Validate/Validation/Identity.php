<?php
/**
 * Класс валидации Identity
 */
namespace App\Validate\Validation;

use App\Validate\Validation;

/**
 * Class Identity
 * @package App\Validate\Validation
 */
class Identity extends Validation
{
    /**
     * @var string
     */
    private string $dataConfirmation;

    /**
     * Identity constructor.
     * @param string $data
     * @param string $dataConfirmation
     */
    public function __construct(string $data = '', string $dataConfirmation = '')
    {
        $this->data = $data;
        $this->dataConfirmation = $dataConfirmation;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->AreObjectsIdentically();
    }

    /**
     * @return bool
     */
    private function AreObjectsIdentically(): bool
    {
        if(empty($this->data) || empty($this->dataConfirmation)) {
            return false;
        }

        if($this->data === $this->dataConfirmation) {
            return true;
        }

        $this->message = "Поля не совпадают";
        return false;
    }
}
