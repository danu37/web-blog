<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Support\HtmlSanitizer;
use App\Support\PublicDataMapper;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('q', ''));

        $posts = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->when($search !== '', function ($query) use ($search): void {
                $query->where(function ($sub) use ($search): void {
                    $sub->where('title', 'like', '%'.$search.'%')
                        ->orWhere('excerpt', 'like', '%'.$search.'%');
                });
            })
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString()
            ->through(fn (Post $post) => PublicDataMapper::postSummary($post));

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => [
                'q' => $search,
            ],
            'archiveTitle' => 'Journal',
            'archiveDescription' => 'Essays, field notes, and visual monographs.',
        ]);
    }

    public function show(string $slug): Response
    {
        $post = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->where('slug', $slug)
            ->firstOrFail();

        $related = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn ($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->take(3)
            ->get()
            ->map(fn (Post $item) => PublicDataMapper::postSummary($item));

        $latest = Post::published()
            ->with(['category', 'featuredImage'])
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(4)
            ->get()
            ->map(fn (Post $item) => PublicDataMapper::postSummary($item));

        return Inertia::render('Blog/Show', [
            'post' => array_merge(PublicDataMapper::postSummary($post), [
                'content' => HtmlSanitizer::clean($post->content),
            ]),
            'relatedPosts' => $related,
            'latestPosts' => $latest,
        ]);
    }

    public function category(string $slug): Response
    {
        $category = Category::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->where('category_id', $category->id)
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString()
            ->through(fn (Post $post) => PublicDataMapper::postSummary($post));

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'activeCategory' => $category->slug,
            'filters' => ['q' => ''],
            'archiveTitle' => $category->name,
            'archiveDescription' => $category->description ?: 'Selected stories filed under '.$category->name.'.',
        ]);
    }

    public function tag(string $slug): Response
    {
        $tag = Tag::query()->where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->with(['user', 'category', 'featuredImage', 'tags'])
            ->whereHas('tags', fn ($q) => $q->where('tags.id', $tag->id))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString()
            ->through(fn (Post $post) => PublicDataMapper::postSummary($post));

        $categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'activeTag' => $tag->slug,
            'filters' => ['q' => ''],
            'archiveTitle' => '#'.$tag->name,
            'archiveDescription' => 'Articles tagged with '.$tag->name.'.',
        ]);
    }
}
