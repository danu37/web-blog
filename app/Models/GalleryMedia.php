<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class GalleryMedia extends Pivot
{
    use HasUuids;

    protected $table = 'gallery_media';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }
}
