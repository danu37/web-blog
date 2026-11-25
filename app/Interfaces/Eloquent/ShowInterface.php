<?php

namespace App\Interfaces\Eloquent;

interface ShowInterface
{
    /**
     * Handle get the specified data by id from models.
     */
    public function show(mixed $id): mixed;
}
