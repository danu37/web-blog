<?php

namespace App\Interfaces\Eloquent;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface CustomPaginationInterface
{
    /**
     * Handle paginate data event from models.
     */
    public function customPaginate(Request $request, int $pagination = 10): LengthAwarePaginator;
}
