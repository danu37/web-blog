<?php

namespace App\Interfaces\Eloquent;

interface UpdateSoftDelete
{
    /**
     * Handle show method and update data instantly from soft deleted models.
     */
    public function updateSoftDelete(mixed $id, array $data): mixed;
}
