<?php
/**
 * Модель SystemSetting
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'system_settings';
    protected $primaryKey = 'id';

    protected $fillable = ['value'];

}
