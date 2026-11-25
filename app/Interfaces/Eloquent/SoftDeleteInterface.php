<?php

namespace App\Interfaces\Eloquent;

interface SoftDeleteInterface
{
    /**
     * Implement soft delete method
     */
    public function softDelete(mixed $id): mixed;
}
