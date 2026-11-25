<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Media;
use App\Support\PublicDataMapper;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    public function index(): Response
    {
        $galleries = Gallery::published()
            ->with('media')
            ->latest('published_at')
            ->paginate(9)
            ->through(fn (Gallery $gallery) => PublicDataMapper::gallerySummary($gallery));

        return Inertia::render('Gallery/Index', [
            'galleries' => $galleries,
        ]);
    }

    public function show(string $slug): Response
    {
        $gallery = Gallery::published()
            ->with('media')
            ->where('slug', $slug)
            ->firstOrFail();

        return Inertia::render('Gallery/Show', [
            'gallery' => [
                'id' => $gallery->id,
                'title' => $gallery->title,
                'slug' => $gallery->slug,
                'description' => $gallery->description,
                'published_at_formatted' => $gallery->published_at?->format('M d, Y'),
                'media' => $gallery->media->map(
                    fn (Media $media) => PublicDataMapper::mediaItem($media, $gallery->title)
                )->values()->all(),
            ],
        ]);
    }
}
