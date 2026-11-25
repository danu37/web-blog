<?php

namespace App\Interfaces\Eloquent;

interface SumInterface
{
    /**
     * Handle sum data event from models.
     */
    public function sum(): int;
}
