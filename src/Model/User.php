<?php
/**
 * Класс User
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'users';

    /**
     * Validation rules
     */
    public $rules = [
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => ['required', 'between:6,255', 'email', 'unique'],
        'password' => ['required', 'between:6,255'],
        'password_confirm' => ['identityWith:password', 'between:6,255']
    ];

    /**
     * @var array Attributes that should be cast to dates
     */
    protected $dates = [
        'activated_at',
        'last_login',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function role()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

}
