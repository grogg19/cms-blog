<?php
/**
 * Модель StaticPage
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    public $rules = [
        'title'   => 'required',
        'url'    => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i'],
        'content' => 'required'
    ];
}
