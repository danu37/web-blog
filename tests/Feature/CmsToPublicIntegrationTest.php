<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Media;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class CmsToPublicIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_settings_changes_reflect_on_public_pages(): void
    {
        SiteSetting::create(['key' => 'site_name', 'value' => 'ATELIER NOIR', 'type' => 'text']);
        SiteSetting::create(['key' => 'contact_email', 'value' => 'studio@ateliernoir.test', 'type' => 'email']);

        $this->get('/contact')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('site.name', 'ATELIER NOIR')
                ->where('site.contactEmail', 'studio@ateliernoir.test')
            );
    }

    public function test_homepage_section_toggle_and_reorder_reflect_on_homepage(): void
    {
        $hero = HomepageSection::create([
            'title' => 'Hero Masthead',
            'section_key' => 'hero',
            'type' => 'hero',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $grid = HomepageSection::create([
            'title' => 'Latest Essays',
            'section_key' => 'article_grid',
            'type' => 'article_grid',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        // Reorder and disable hero
        $hero->update(['is_active' => false, 'sort_order' => 5]);
        $grid->update(['sort_order' => 1]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('sections', 1)
                ->where('sections.0.key', 'article_grid')
            );
    }

    public function test_post_lifecycle_draft_published_archived_soft_delete_controls_public_visibility(): void
    {
        $post = Post::factory()->draft()->create([
            'title' => 'Lifecycle Essay',
            'slug' => 'lifecycle-essay',
        ]);

        $this->get('/blog/lifecycle-essay')->assertNotFound();

        $post->update([
            'status' => 'published',
            'published_at' => now()->subMinute(),
        ]);

        $this->get('/blog/lifecycle-essay')->assertOk();

        $post->update(['status' => 'archived']);
        $this->get('/blog/lifecycle-essay')->assertNotFound();

        $post->update(['status' => 'published']);
        $post->delete();
        $this->get('/blog/lifecycle-essay')->assertNotFound();
    }

    public function test_gallery_reordering_updates_public_gallery_sequence(): void
    {
        $user = User::factory()->admin()->create();
        $mediaOne = Media::factory()->create(['user_id' => $user->id]);
        $mediaTwo = Media::factory()->create(['user_id' => $user->id]);

        $gallery = Gallery::factory()->create([
            'slug' => 'reordered-series',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        $gallery->media()->attach([
            $mediaOne->id => ['sort_order' => 1, 'caption' => 'Original First'],
            $mediaTwo->id => ['sort_order' => 2, 'caption' => 'Original Second'],
        ]);

        // Swap sort order in CMS
        $gallery->media()->updateExistingPivot($mediaOne->id, ['sort_order' => 2]);
        $gallery->media()->updateExistingPivot($mediaTwo->id, ['sort_order' => 1]);

        $this->get('/gallery/reordered-series')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('gallery.media.0.caption', 'Original Second')
                ->where('gallery.media.1.caption', 'Original First')
            );
    }

    public function test_blog_archive_paginates_published_posts(): void
    {
        Post::factory()->count(11)->create([
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('posts.data', 9)
                ->where('posts.total', 11)
            );

        $this->get('/blog?page=2')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('posts.data', 2)
            );
    }

    public function test_media_upload_validation_rejects_svg_and_allows_safe_raster_images(): void
    {
        Storage::fake('public');

        $validImage = UploadedFile::fake()->image('editorial.jpg', 1200, 800);
        $svgFile = UploadedFile::fake()->create('vector.svg', 10, 'image/svg+xml');

        $rules = ['file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:5120']];

        $this->assertTrue(Validator::make(['file' => $validImage], $rules)->passes());
        $this->assertTrue(Validator::make(['file' => $svgFile], $rules)->fails());
    }
}
