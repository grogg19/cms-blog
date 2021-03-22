<?php
/**
 * Модель Post
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Image;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blog_posts';
    protected $primaryKey = 'id';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $fillable = ['user_id'];

    public $rules = [
        'title'   => 'required',
        'slug'    => ['required', 'regex:/^[a-z0-9\/\:_\-\*\[\]\+\?\|]*$/i'],
        'content' => 'required',
        'excerpt' => 'required',
        'published_at' => 'required'
    ];

    /**
     * @return array
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
