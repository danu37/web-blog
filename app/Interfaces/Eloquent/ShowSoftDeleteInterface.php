<?php

namespace App\Interfaces\Eloquent;

interface ShowSoftDeleteInterface
{
    /**
     * Implement show soft delete method
     */
    public function showSoftDelete(mixed $id): mixed;
}
