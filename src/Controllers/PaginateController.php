<?php
/**
 * Class PaginateController
 */

namespace App\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

/**
 * Class PaginateController
 * @package App\Controllers
 */
class PaginateController extends Controller
{
    /**
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    function paginate($items, int $perPage = 5, $page = null, array $options = []): LengthAwarePaginator
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }
}
