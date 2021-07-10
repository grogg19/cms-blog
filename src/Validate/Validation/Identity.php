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
        parent::__construct();

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
     * реализация валидации
     * @return bool
     */
    private function AreObjectsIdentically(): bool
    {
        if (empty($this->data) || empty($this->dataConfirmation)) {
            $this->message = "Поля не совпадают";
            return false;
        }

        if ($this->data === $this->dataConfirmation) {
            return true;
        }

        $this->message = "Поля не совпадают";
        return false;
    }
}
