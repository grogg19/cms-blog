<?php
/**
 * Класс IsUniqueModel
 */
namespace App\Validate\Validation;

use App\Validate\Validation;
use Illuminate\Database\Eloquent\Model;

class IsUniqueModel extends Validation
{

    private string $key;
    private Model $model;

    /**
     * IsUniqueModel constructor.
     * @param Model $model
     * @param string $key
     * @param string $data
     */
    public function __construct(Model $model, string $key, string $data)
    {
        $this->data = $data;
        $this->model = $model;
        $this->key = $key;
    }

    /**
     * Метод запускает реализацию
     * @return bool
     */
    public function run(): bool
    {
        return $this->isUnique();
    }

    /**
     * @return bool
     */
    private function isUnique(): bool
    {
        return !$this->model::where($this->key, $this->data)->count();
    }
}