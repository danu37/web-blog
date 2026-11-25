<?php

namespace App\Interfaces\Eloquent;

interface GetAllInterface
{
    /**
     * Handle the Get all data event from models.
     */
    public function getAll(): mixed;
}
