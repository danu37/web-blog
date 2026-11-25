<?php

namespace App\Interfaces\Eloquent;

interface ShowSlugInterface
{
    /**
     * Handle get the specified data by id from models.
     */
    public function showWithSlug(string $slug): mixed;
}
