<?php
/**
 * Class StaticPage - model
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    public static function where(string $attribute, string $value): string
    {
        dump('its where()');
        return self::class;
    }

    public function count(): StaticPage
    {
        dump('its count()');
        return $this;
    }
}
