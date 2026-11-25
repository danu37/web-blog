<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database for local/development environments only.
     */
    public function run(): void
    {
        if (app()->isProduction()) {
            return;
        }

        $adminPassword = env('SEED_ADMIN_PASSWORD') ?: Str::random(24);
        $editorPassword = env('SEED_EDITOR_PASSWORD') ?: Str::random(24);

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@editorial.test'],
            [
                'name' => 'Editorial Admin',
                'password' => Hash::make($adminPassword),
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );

        $editor = User::query()->firstOrCreate(
            ['email' => 'editor@editorial.test'],
            [
                'name' => 'Contributing Editor',
                'password' => Hash::make($editorPassword),
                'role' => 'editor',
                'email_verified_at' => now(),
            ],
        );

        $categories = collect([
            ['name' => 'Monochrome Studies', 'slug' => 'monochrome-studies', 'description' => 'Light, shadow, and architectural stillness in black and white.'],
            ['name' => 'Field Notes', 'slug' => 'field-notes', 'description' => 'Dispatches from coastal roads, studios, and quiet landscapes.'],
            ['name' => 'Portraits', 'slug' => 'portraits', 'description' => 'Intimate character studies captured on medium format film.'],
        ])->map(fn (array $data) => Category::query()->firstOrCreate(['slug' => $data['slug']], $data));

        $tags = collect([
            ['name' => '35mm Film', 'slug' => '35mm-film'],
            ['name' => 'Medium Format', 'slug' => 'medium-format'],
            ['name' => 'Architecture', 'slug' => 'architecture'],
            ['name' => 'Minimalism', 'slug' => 'minimalism'],
        ])->map(fn (array $data) => Tag::query()->firstOrCreate(['slug' => $data['slug']], $data));

        $samplePosts = [
            [
                'title' => 'Quiet Geometry at First Light',
                'slug' => 'quiet-geometry-at-first-light',
                'excerpt' => 'Observing how early coastal fog softens concrete edges along the harbor.',
                'content' => '<p>Before the city wakes, raw concrete holds the faintest silver reflection of dawn. Walking the promenade with a single prime lens forces patience over speed.</p><p>Each frame becomes an exercise in subtraction—removing distraction until only proportion and shadow remain.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $admin->id,
                'category_id' => $categories[0]->id,
            ],
            [
                'title' => 'Notes from the Northern Ridge',
                'slug' => 'notes-from-the-northern-ridge',
                'excerpt' => 'Three days documenting shifting weather across high-altitude pine trails.',
                'content' => '<p>Low cloud moves faster than light meters can settle. Shooting by instinct reveals textures in wet basalt and cedar bark that midday sun flattens.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'user_id' => $editor->id,
                'category_id' => $categories[1]->id,
            ],
            [
                'title' => 'Unfinished Studio Monograph',
                'slug' => 'unfinished-studio-monograph',
                'excerpt' => 'Working draft exploring natural window light in small ceramic workshops.',
                'content' => '<p>Draft notes for an upcoming winter essay.</p>',
                'status' => 'draft',
                'published_at' => null,
                'user_id' => $editor->id,
                'category_id' => $categories[2]->id,
            ],
        ];

        foreach ($samplePosts as $index => $postData) {
            $post = Post::query()->firstOrCreate(['slug' => $postData['slug']], $postData);
            $post->tags()->syncWithoutDetaching([$tags[$index % $tags->count()]->id]);
        }

        Page::query()->firstOrCreate(
            ['slug' => 'about'],
            [
                'title' => 'About the Journal',
                'content' => '<p>An independent photography and editorial journal dedicated to slow visual storytelling, monochrome craft, and spatial stillness.</p>',
                'status' => 'published',
            ],
        );

        Page::query()->firstOrCreate(
            ['slug' => 'contact'],
            [
                'title' => 'Contact',
                'content' => '<p>For editorial commissions, print inquiries, or collaborations, reach out via our studio email.</p>',
                'status' => 'published',
            ],
        );

        Gallery::query()->firstOrCreate(
            ['slug' => 'coastal-monochrome'],
            [
                'title' => 'Coastal Monochrome',
                'description' => 'Selected black-and-white frames from winter tides.',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
        );

        $settings = [
            ['key' => 'site_name', 'value' => 'NOIR & FRAME', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'A minimal editorial photography journal.', 'type' => 'text'],
            ['key' => 'footer_text', 'value' => 'Independent editorial photography & essays.', 'type' => 'text'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->firstOrCreate(['key' => $setting['key']], $setting);
        }

        $sections = [
            ['section_key' => 'hero', 'title' => 'NOIR & FRAME', 'type' => 'hero', 'description' => 'Visual essays in light, shadow, and stillness.', 'sort_order' => 1, 'is_active' => true],
            ['section_key' => 'featured_post', 'title' => 'Featured Essay', 'type' => 'featured_post', 'description' => null, 'sort_order' => 2, 'is_active' => true],
            ['section_key' => 'editorial_intro', 'title' => 'Editorial Note', 'type' => 'editorial_intro', 'description' => 'Curated dispatches on photography, architecture, and quiet craft.', 'sort_order' => 3, 'is_active' => true],
            ['section_key' => 'article_grid', 'title' => 'Latest Stories', 'type' => 'article_grid', 'description' => null, 'sort_order' => 4, 'is_active' => true],
            ['section_key' => 'gallery_strip', 'title' => 'Selected Frames', 'type' => 'gallery_strip', 'description' => null, 'sort_order' => 5, 'is_active' => true],
        ];

        foreach ($sections as $section) {
            HomepageSection::query()->firstOrCreate(['section_key' => $section['section_key']], $section);
        }
    }
}
