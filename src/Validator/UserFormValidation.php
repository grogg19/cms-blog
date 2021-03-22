<?php
/**
 * Класс UserFormValidation
 */

namespace App\Validator;

use App\Validator\Validation as Validation;
use App\Model\User;

/**
 * Class UserFormValidation
 * @package App\Validation
 */
class UserFormValidation extends Validation
{
    public $model;

    public function __construct()
    {
        $this->model = User::class;
    }

}
