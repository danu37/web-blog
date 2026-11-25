<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Support\HtmlSanitizer;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function about(): Response
    {
        $page = Page::published()->where('slug', 'about')->first();

        return Inertia::render('Pages/Show', [
            'page' => [
                'title' => $page?->title ?? 'About',
                'slug' => 'about',
                'content' => HtmlSanitizer::clean($page?->content),
            ],
            'isContact' => false,
        ]);
    }

    public function contact(): Response
    {
        $page = Page::published()->where('slug', 'contact')->first();

        return Inertia::render('Pages/Show', [
            'page' => [
                'title' => $page?->title ?? 'Contact',
                'slug' => 'contact',
                'content' => HtmlSanitizer::clean($page?->content),
            ],
            'isContact' => true,
        ]);
    }
}
