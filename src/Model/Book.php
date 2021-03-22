<?php
/**
 * Модель Book
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'books';
    protected $primaryKey = 'id';

}
