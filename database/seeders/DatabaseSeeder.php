<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

        $seedSpecs = [
            'hero' => ['title' => 'Hero Atmospheric Backdrop', 'w' => 1600, 'h' => 900, 'bg' => [20, 22, 28], 'accent' => [180, 107, 53]],
            'portrait' => ['title' => 'Editorial Monochrome Portrait', 'w' => 900, 'h' => 1200, 'bg' => [26, 28, 35], 'accent' => [210, 206, 198]],
            'shop-1' => ['title' => 'Coastal Monograph Print', 'w' => 960, 'h' => 800, 'bg' => [24, 26, 32], 'accent' => [180, 107, 53]],
            'shop-2' => ['title' => 'Issue 04 Print Zine', 'w' => 960, 'h' => 800, 'bg' => [30, 32, 40], 'accent' => [225, 220, 210]],
            'shop-3' => ['title' => 'Medium Format Contact Poster', 'w' => 960, 'h' => 800, 'bg' => [18, 20, 25], 'accent' => [160, 110, 68]],
            'post-1' => ['title' => 'Coastal Concrete Dawn Study', 'w' => 900, 'h' => 1200, 'bg' => [28, 32, 38], 'accent' => [180, 107, 53]],
            'post-2' => ['title' => 'Northern Ridge Basalt Fog', 'w' => 900, 'h' => 1200, 'bg' => [22, 26, 30], 'accent' => [195, 190, 180]],
            'post-3' => ['title' => 'Harbor Timber Geometry', 'w' => 900, 'h' => 1200, 'bg' => [34, 28, 26], 'accent' => [180, 107, 53]],
            'post-4' => ['title' => 'Silver Halide Street Reflections', 'w' => 900, 'h' => 1200, 'bg' => [20, 22, 26], 'accent' => [215, 212, 205]],
            'post-5' => ['title' => 'Minimalist Ceramic Studio Light', 'w' => 900, 'h' => 1200, 'bg' => [30, 28, 25], 'accent' => [180, 107, 53]],
            'post-6' => ['title' => 'Dusk Over Old Warehouse District', 'w' => 900, 'h' => 1200, 'bg' => [24, 24, 30], 'accent' => [170, 120, 75]],
        ];

        $mediaMap = [];
        foreach ($seedSpecs as $key => $spec) {
            $path = "media/seed/{$key}.jpg";
            $size = $this->ensureSeedImage($path, $spec['w'], $spec['h'], $spec['bg'], $spec['accent']);

            $mediaMap[$key] = Media::query()->firstOrCreate(
                ['file_path' => $path],
                [
                    'user_id' => $admin->id,
                    'disk' => 'public',
                    'file_name' => "{$key}.jpg",
                    'mime_type' => 'image/jpeg',
                    'file_size' => $size,
                    'alt_text' => $spec['title'],
                ],
            );
        }

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
                'excerpt' => 'Observing how early coastal fog softens raw concrete edges along the harbor promenade before the city wakes.',
                'content' => '<p>Before the city wakes, raw concrete holds the faintest silver reflection of dawn. Walking the promenade with a single prime lens forces patience over speed.</p><blockquote>Patience turns ordinary concrete and morning mist into quiet architectural rhythm.</blockquote><p>Each frame becomes an exercise in subtraction—removing distraction until only proportion and shadow remain.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(6),
                'user_id' => $admin->id,
                'category_id' => $categories[0]->id,
                'featured_image_id' => $mediaMap['post-1']->id,
            ],
            [
                'title' => 'Notes from the Northern Ridge',
                'slug' => 'notes-from-the-northern-ridge',
                'excerpt' => 'Three days documenting shifting weather across high-altitude pine trails with a mechanical rangefinder.',
                'content' => '<p>Low cloud moves faster than light meters can settle. Shooting by instinct reveals textures in wet basalt and cedar bark that midday sun flattens.</p><h2>Working in Shifting Mist</h2><p>Exposure decisions become intuitive when contrast narrows to subtle charcoal tones.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'user_id' => $editor->id,
                'category_id' => $categories[1]->id,
                'featured_image_id' => $mediaMap['post-2']->id,
            ],
            [
                'title' => 'Timber Pilings and Low Tide',
                'slug' => 'timber-pilings-and-low-tide',
                'excerpt' => 'Long exposures along quiet wooden piers where water meets weathered oak.',
                'content' => '<p>When the tide recedes at dusk, weathered pilings stand like ink strokes against slate water.</p><p>Using a four-second shutter turns ripples into matte silk.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'user_id' => $admin->id,
                'category_id' => $categories[0]->id,
                'featured_image_id' => $mediaMap['post-3']->id,
            ],
            [
                'title' => 'After Rain on Narrow Alleys',
                'slug' => 'after-rain-on-narrow-alleys',
                'excerpt' => 'Tracing warm sodium streetlights mirrored across wet cobblestones on high-speed monochrome film.',
                'content' => '<p>Night photography begins twenty minutes after rainfall stops, while puddles still hold sharp reflections of shopfront glass.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'user_id' => $editor->id,
                'category_id' => $categories[1]->id,
                'featured_image_id' => $mediaMap['post-4']->id,
            ],
            [
                'title' => 'Window Light in the Ceramic Studio',
                'slug' => 'window-light-in-the-ceramic-studio',
                'excerpt' => 'A portrait study of craftspeople shaping raw stoneware under north-facing skylight.',
                'content' => '<p>Soft directional light from a single tall window reveals clay dust, worn wooden tools, and quiet concentration.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'user_id' => $admin->id,
                'category_id' => $categories[2]->id,
                'featured_image_id' => $mediaMap['post-5']->id,
            ],
            [
                'title' => 'Dusk Over the Old Warehouse District',
                'slug' => 'dusk-over-the-old-warehouse-district',
                'excerpt' => 'Studying brick facades and steel fire escapes during the brief amber hour before nightfall.',
                'content' => '<p>Industrial architecture carries weight when photographed at eye level without distortion, letting vertical brick lines anchor the frame.</p>',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'user_id' => $editor->id,
                'category_id' => $categories[0]->id,
                'featured_image_id' => $mediaMap['post-6']->id,
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
                'featured_image_id' => null,
            ],
        ];

        foreach ($samplePosts as $index => $postData) {
            $post = Post::query()->updateOrCreate(['slug' => $postData['slug']], $postData);
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

        $gallery = Gallery::query()->firstOrCreate(
            ['slug' => 'coastal-monochrome'],
            [
                'title' => 'Coastal Monochrome',
                'description' => 'Selected black-and-white frames from winter tides.',
                'status' => 'published',
                'published_at' => now()->subDays(3),
            ],
        );

        foreach (['post-1', 'post-2', 'post-3', 'post-4', 'post-5', 'post-6'] as $order => $key) {
            $gallery->media()->syncWithoutDetaching([
                $mediaMap[$key]->id => [
                    'sort_order' => $order + 1,
                    'caption' => $seedSpecs[$key]['title'],
                ],
            ]);
        }

        $settings = [
            ['key' => 'site_name', 'value' => 'GARUDA STERIDA', 'type' => 'text'],
            ['key' => 'site_description', 'value' => 'LE TRUPPE NERE ROSSE — Komunitas suporter dan arsip editorial.', 'type' => 'text'],
            ['key' => 'footer_text', 'value' => 'GARUDA STERIDA — LE TRUPPE NERE ROSSE.', 'type' => 'text'],
            ['key' => 'contact_email', 'value' => 'studio@garudasterida.test', 'type' => 'email'],
            ['key' => 'contact_location', 'value' => 'Sleman, Yogyakarta', 'type' => 'text'],
            ['key' => 'instagram_url', 'value' => 'https://instagram.com', 'type' => 'url'],
            ['key' => 'youtube_url', 'value' => 'https://youtube.com', 'type' => 'url'],
            ['key' => 'x_url', 'value' => 'https://x.com', 'type' => 'url'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::query()->updateOrCreate(['key' => $setting['key']], $setting);
        }

        $sections = [
            [
                'section_key' => 'hero',
                'title' => 'GARUDA STERIDA',
                'type' => 'hero',
                'description' => 'LE TRUPPE NERE ROSSE',
                'sort_order' => 1,
                'is_active' => true,
                'settings' => [
                    'background_image' => $mediaMap['hero']->url,
                ],
            ],
            [
                'section_key' => 'editorial_intro',
                'title' => 'SUICIDE SQUAD 11.5',
                'type' => 'editorial_intro',
                'description' => '"Suicide Squad 11.5" is a community of supporters dedicated to the PSS Sleman football club. The name "Suicide Squad 11.5" describes the enthusiasm and courage of its members in supporting their favorite team.',
                'sort_order' => 2,
                'is_active' => true,
                'settings' => [
                    'eyebrow' => 'Who We Are',
                    'portrait_image' => $mediaMap['portrait']->url,
                    'secondary_description' => '"Suicide Squad 11.5" is not just a supporter group, but also a family for its members. We provide a platform for the exchange of ideas, experiences and social activities that strengthen relationships among fellow football fans. With a spirit of togetherness and high dedication, we continue to be an important pillar in maintaining the Sleman football tradition.',
                ],
            ],
            [
                'section_key' => 'projects',
                'title' => 'Our Shop',
                'type' => 'projects',
                'description' => null,
                'sort_order' => 3,
                'is_active' => true,
                'settings' => [
                    'eyebrow' => 'Best Choice',
                    'items' => json_encode([
                        [
                            'title' => 'Black short-sleeve t-shirt featuring an eagle graphic and bold',
                            'price_label' => 'IDR',
                            'price_value' => '84,000.00',
                            'image_url' => $mediaMap['shop-1']->url,
                        ],
                        [
                            'title' => 'Black long-sleeve graphic tee with distinct sleeve prints and edgy text design',
                            'price_label' => 'IDR',
                            'price_value' => '84,000.00',
                            'image_url' => $mediaMap['shop-2']->url,
                        ],
                        [
                            'title' => 'Black graphic t-shirt styled in an urban street setting with a creative motion blur',
                            'price_label' => 'IDR',
                            'price_value' => '84,000.00',
                            'image_url' => $mediaMap['shop-3']->url,
                        ],
                    ]),
                ],
            ],
            [
                'section_key' => 'article_grid',
                'title' => 'Speech For The Pride',
                'type' => 'article_grid',
                'description' => null,
                'sort_order' => 4,
                'is_active' => true,
                'settings' => [
                    'eyebrow' => 'Our Blog',
                    'cta_label' => 'Load more',
                    'cta_url' => '/blog',
                ],
            ],
            [
                'section_key' => 'gallery_strip',
                'title' => 'Selected Frames',
                'type' => 'gallery_strip',
                'description' => null,
                'sort_order' => 5,
                'is_active' => true,
                'settings' => null,
            ],
            [
                'section_key' => 'category_digest',
                'title' => 'Latest Stories',
                'type' => 'category_digest',
                'description' => null,
                'sort_order' => 6,
                'is_active' => true,
                'settings' => null,
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::query()->updateOrCreate(['section_key' => $section['section_key']], $section);
        }
    }

    /**
     * Generate a clean, original dark editorial placeholder JPEG on the public disk if missing.
     */
    private function ensureSeedImage(string $path, int $width, int $height, array $bgRgb, array $accentRgb): int
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($path) && function_exists('imagecreatetruecolor')) {
            $img = imagecreatetruecolor($width, $height);
            $bg = imagecolorallocate($img, $bgRgb[0], $bgRgb[1], $bgRgb[2]);
            $mid = imagecolorallocate($img, min(255, $bgRgb[0] + 18), min(255, $bgRgb[1] + 18), min(255, $bgRgb[2] + 22));
            $accent = imagecolorallocate($img, $accentRgb[0], $accentRgb[1], $accentRgb[2]);
            $line = imagecolorallocate($img, 54, 58, 70);

            imagefill($img, 0, 0, $bg);

            // Subtle architectural gradient bands and minimal geometric horizon
            for ($y = (int) ($height * 0.45); $y < $height; $y++) {
                $ratio = ($y - $height * 0.45) / max(1, $height * 0.55);
                $r = (int) max(8, $bgRgb[0] - $ratio * 10);
                $g = (int) max(8, $bgRgb[1] - $ratio * 10);
                $b = (int) max(10, $bgRgb[2] - $ratio * 10);
                $shade = imagecolorallocate($img, $r, $g, $b);
                imageline($img, 0, $y, $width, $y, $shade);
            }

            // Minimal editorial frame / horizon composition
            imagerectangle($img, (int) ($width * 0.12), (int) ($height * 0.14), (int) ($width * 0.88), (int) ($height * 0.86), $line);
            imagefilledellipse($img, (int) ($width * 0.5), (int) ($height * 0.42), (int) ($width * 0.28), (int) ($width * 0.28), $mid);
            imageline($img, (int) ($width * 0.22), (int) ($height * 0.65), (int) ($width * 0.78), (int) ($height * 0.65), $accent);

            ob_start();
            imagejpeg($img, null, 88);
            $bytes = (string) ob_get_clean();
            imagedestroy($img);

            $disk->put($path, $bytes);
        }

        return $disk->exists($path) ? (int) $disk->size($path) : 0;
    }
}
