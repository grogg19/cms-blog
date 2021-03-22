<?php
/**
 * Класс ролей пользователя UserRole
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\User;

class UserRole extends Model
{

    /**
     * @var string The database table used by the model.
     */
    protected $table = 'user_roles';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|between:2,128|unique:user_roles',
        'code' => 'unique:user_roles',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}