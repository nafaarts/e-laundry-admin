<?php

namespace App\Helpers;

use Illuminate\Container\Container;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class PaginationHelper
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Creates a new array paginator instance.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * Paginates an array of items.
     *
     * @param mixed   $items
     * @param integer $length
     *
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public function paginate($items, $length = 10)
    {
        if ($items instanceof Collection) {
            $items = $items->all();
        }

        $page = $this->request->get('page') ?: 1;

        $offset = ($page - 1) * $length;

        $paginator = new LengthAwarePaginator(array_slice($items, $offset, $length), count($items), $length);

        $paginator->setPath(url($this->request->path()));

        return $paginator;
    }
}
