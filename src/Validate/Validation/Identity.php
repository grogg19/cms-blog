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
    public function __construct(string $data, string $dataConfirmation)
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
        if($this->data !== $this->dataConfirmation) {
            $this->message = "Поле не совпадает";
            return false;
        }
        return true;
    }
}
