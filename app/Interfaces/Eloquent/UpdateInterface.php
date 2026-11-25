<?php

namespace App\Interfaces\Eloquent;

interface UpdateInterface
{
    /**
     * Handle show method and update data instantly from models.
     */
    public function update(mixed $id, array $data): mixed;
}
