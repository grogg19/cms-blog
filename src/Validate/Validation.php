<?php
/**
 * Класс Validation
 */
namespace App\Validate;

/**
 * Class Validation
 * @package App\Validate
 */
abstract class Validation
{
    public function __construct()
    {

    }

    /**
     * @var string
     */
    protected string $message = '';

    /**
     * @var string
     */
    protected string $data;

    /**
     * @return bool
     */
    abstract public function run(): bool;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

}
