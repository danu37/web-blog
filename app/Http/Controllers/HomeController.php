<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Media;
use App\Models\Post;
use App\Support\PublicDataMapper;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $sections = HomepageSection::active()->ordered()->get();

        $posts = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->latest('published_at')
            ->take(10)
            ->get();

        $featuredPost = $posts->first();
        $gridPosts = $posts->slice(1, 6)->values();

        $galleryMedia = Gallery::published()
            ->with('media')
            ->latest('published_at')
            ->take(3)
            ->get()
            ->flatMap(fn (Gallery $gallery) => $gallery->media->map(fn (Media $media) => [
                'id' => $media->id,
                'url' => $media->url,
                'alt' => $media->alt_text ?: $gallery->title,
                'caption' => $media->pivot?->caption,
                'gallery_slug' => $gallery->slug,
                'gallery_title' => $gallery->title,
            ]))
            ->take(8)
            ->values();

        $payload = $sections->map(function (HomepageSection $section) use ($featuredPost, $gridPosts, $galleryMedia): array {
            $data = [
                'id' => $section->id,
                'key' => $section->section_key,
                'type' => $section->type,
                'title' => $section->title,
                'description' => $section->description,
                'settings' => $section->settings ?? [],
            ];

            if ($section->type === 'featured_post') {
                $data['post'] = $featuredPost ? PublicDataMapper::postSummary($featuredPost) : null;
            } elseif ($section->type === 'article_grid') {
                $data['posts'] = $gridPosts->map(fn (Post $post) => PublicDataMapper::postSummary($post))->all();
            } elseif ($section->type === 'gallery_strip') {
                $data['items'] = $galleryMedia->all();
            }

            return $data;
        })->values();

        return Inertia::render('Home', [
            'sections' => $payload,
        ]);
    }
}
