<?php

namespace App\Interfaces\Eloquent;

interface CountInterface
{
    /**
     * Handle count all data event from models.
     */
    public function count(): int;
}
