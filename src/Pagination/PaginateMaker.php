<?php
/**
 * Class PaginateMaker
 */

namespace App\Pagination;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class PaginateMaker
 * @package App\Pagination
 */
class PaginateMaker
{
    /**
     * Создает и возвращает пагинацию из коллекции
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    public function paginate($items, int $perPage = 5, $page = null, array $options = []): LengthAwarePaginator
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }
}
