<?php
/**
 *  Class PostFormValidation
 */

namespace App\Validator;

use App\Validator\Validation as Validation;
use App\Model\Post;

class PostFormValidation extends Validation
{
    public $model;

    public function __construct()
    {
        $this->model = Post::class;
    }
}
