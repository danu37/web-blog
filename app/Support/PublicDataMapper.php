<?php

namespace App\Support;

use App\Models\Gallery;
use App\Models\Media;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Str;

class PublicDataMapper
{
    public static function siteSettings(): array
    {
        $settings = SiteSetting::query()->pluck('value', 'key')->all();

        return [
            'name' => $settings['site_name'] ?? config('app.name', 'NOIR & FRAME'),
            'description' => $settings['site_description'] ?? 'An independent editorial photography journal.',
            'logoUrl' => self::resolveMediaPath($settings['logo_path'] ?? null),
            'faviconUrl' => self::resolveMediaPath($settings['favicon_path'] ?? null),
            'footerText' => $settings['footer_text'] ?? 'Independent editorial photography & visual essays.',
            'contactEmail' => $settings['contact_email'] ?? null,
            'contactLocation' => $settings['contact_location'] ?? null,
            'instagramUrl' => $settings['instagram_url'] ?? null,
            'youtubeUrl' => $settings['youtube_url'] ?? null,
            'xUrl' => $settings['x_url'] ?? null,
            'year' => now()->year,
        ];
    }

    public static function postSummary(Post $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt ?: Str::limit(strip_tags((string) $post->content), 150),
            'published_at' => $post->published_at?->toIso8601String(),
            'published_at_formatted' => $post->published_at?->format('M d, Y'),
            'reading_time' => max(1, (int) ceil(str_word_count(strip_tags((string) $post->content)) / 200)),
            'category' => $post->category ? [
                'name' => $post->category->name,
                'slug' => $post->category->slug,
            ] : null,
            'author' => $post->user ? [
                'name' => $post->user->name,
            ] : null,
            'featured_image' => self::mediaItem($post->featuredImage, $post->title),
            'tags' => $post->relationLoaded('tags')
                ? $post->tags->map(fn ($tag) => ['name' => $tag->name, 'slug' => $tag->slug])->values()->all()
                : [],
        ];
    }

    public static function gallerySummary(Gallery $gallery): array
    {
        $cover = $gallery->media->first();

        return [
            'id' => $gallery->id,
            'title' => $gallery->title,
            'slug' => $gallery->slug,
            'description' => $gallery->description,
            'published_at_formatted' => $gallery->published_at?->format('M d, Y'),
            'media_count' => $gallery->media->count(),
            'cover_image' => self::mediaItem($cover, $gallery->title),
        ];
    }

    public static function mediaItem(?Media $media, string $fallbackAlt = ''): ?array
    {
        if (! $media) {
            return null;
        }

        return [
            'id' => $media->id,
            'url' => $media->url,
            'alt' => $media->alt_text ?: $fallbackAlt,
            'caption' => $media->pivot?->caption ?? null,
        ];
    }

    private static function resolveMediaPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) {
            return $path;
        }

        return '/storage/'.ltrim($path, '/');
    }
}
