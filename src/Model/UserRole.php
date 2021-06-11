<?php
/**
 * Класс ролей пользователя UserRole
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserRole
 * @package App\Model
 */
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
        'name' => ['required', 'between:2,128', 'unique'],
        'code' => 'unique',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
