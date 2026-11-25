<?php

namespace App\Interfaces\Eloquent;

use Illuminate\Pagination\LengthAwarePaginator;

interface PaginateInterface
{
    /**
     * Handle paginate data event from models.
     */
    public function paginate(int $pagination = 10): LengthAwarePaginator;
}
