<?php

namespace App\Interfaces\Eloquent;

interface StoreInterface
{
    /**
     * Handle store data event to models.
     */
    public function store(array $data): mixed;
}
