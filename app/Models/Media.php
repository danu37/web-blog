<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'media';

    protected $fillable = [
        'user_id',
        'disk',
        'file_name',
        'file_path',
        'mime_type',
        'file_size',
        'alt_text',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Media $media): bool {
            if ($media->isReferenced()) {
                return false;
            }

            return true;
        });

        static::deleted(function (Media $media): void {
            $disk = $media->disk ?: 'public';
            if ($media->file_path && Storage::disk($disk)->exists($media->file_path)) {
                Storage::disk($disk)->delete($media->file_path);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function featuredInPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'featured_image_id');
    }

    public function galleries(): BelongsToMany
    {
        return $this->belongsToMany(Gallery::class, 'gallery_media')
            ->using(GalleryMedia::class)
            ->withPivot(['id', 'sort_order', 'caption']);
    }

    public function isReferenced(): bool
    {
        if ($this->featuredInPosts()->withTrashed()->exists()) {
            return true;
        }

        if ($this->galleries()->withTrashed()->exists()) {
            return true;
        }

        if (SiteSetting::query()->where('value', $this->id)->orWhere('value', $this->file_path)->exists()) {
            return true;
        }

        if (HomepageSection::query()
            ->where('settings', 'like', '%'.$this->id.'%')
            ->orWhere('settings', 'like', '%'.$this->file_path.'%')
            ->exists()) {
            return true;
        }

        return false;
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk($this->disk ?: 'public')->url($this->file_path);
    }
}
