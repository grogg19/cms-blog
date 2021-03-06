<?php
/**
 * Класс User
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class User
 * @package App\Model
 */
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
        'first_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu'],
        'last_name' => ['required', 'regex:/^[a-zA-Zа-яА-Яё -]+$/iu'],
        'email' => ['required', 'between:6,255', 'email', 'unique'],
        'password' => ['required', 'between:6,255', 'identityWith:password_confirm'],
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

    protected $fillable = [
        'first_name',
        'last_name',
        'password',
        'avatar',
        'self_description',
        'is_activated',
        'role_id'
    ];

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(UserRole::class);
    }

    /**
     * @return HasMany
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return HasOne
     */
    public function subscribeEmail(): HasOne
    {
        return $this->hasOne(Subscriber::class, 'email', 'email');
    }

}
