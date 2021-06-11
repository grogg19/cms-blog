<?php
/**
 * Модель Image
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Image
 * @package App\Model
 */
class Image extends Model
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'blog_post_images';
    protected $primaryKey = 'id';

    /**
     * Validation rules
     */
    public $rules = [
        'file_name' => 'required'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['file_name', 'post_id', 'sort'];

    /**
     * @return BelongsTo
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

}
