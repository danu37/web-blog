<?php

namespace App\Interfaces\Eloquent;

interface DeleteInterface
{
    /**
     * Handle show method and delete data instantly from models.
     */
    public function delete(mixed $id): mixed;
}
