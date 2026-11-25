<?php

namespace App\Interfaces\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Pagination\CursorPaginator;

interface CursorPaginateInterface
{
    /**
     * Handle paginate data event from models.
     *
     * @param  string[]  $columns
     * @param  null  $cursor
     */
    public function cursorPaginate(int $perPage, array $columns, string $cursorName, $cursor, Request $request): CursorPaginator;
}
