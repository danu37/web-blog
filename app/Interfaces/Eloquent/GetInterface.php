<?php

namespace App\Interfaces\Eloquent;

interface GetInterface
{
    /**
     * Handle the Get all data event from models.
     */
    public function get(): mixed;
}
