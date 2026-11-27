<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class PublicFrontendTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_only_active_sections_in_sort_order(): void
    {
        HomepageSection::create([
            'title' => 'Second Section',
            'section_key' => 'second',
            'type' => 'article_grid',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        HomepageSection::create([
            'title' => 'First Section',
            'section_key' => 'first',
            'type' => 'hero',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        HomepageSection::create([
            'title' => 'Hidden Section',
            'section_key' => 'hidden',
            'type' => 'projects',
            'sort_order' => 0,
            'is_active' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('sections', 2)
                ->where('sections.0.key', 'first')
                ->where('sections.1.key', 'second')
            );
    }

    public function test_blog_archive_shows_only_published_posts_and_supports_category_and_tag(): void
    {
        $category = Category::factory()->create(['slug' => 'monochrome']);
        $tag = Tag::factory()->create(['slug' => 'film']);

        $published = Post::factory()->create([
            'title' => 'Published Monochrome Study',
            'slug' => 'published-monochrome-study',
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $published->tags()->attach($tag->id);

        Post::factory()->draft()->create([
            'title' => 'Secret Draft',
            'slug' => 'secret-draft',
            'category_id' => $category->id,
        ]);

        Post::factory()->create([
            'title' => 'Scheduled Future Post',
            'slug' => 'scheduled-future-post',
            'status' => 'published',
            'published_at' => now()->addDays(3),
        ]);

        $this->get('/blog')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Blog/Index')
                ->has('posts.data', 1)
                ->where('posts.data.0.slug', 'published-monochrome-study')
            );

        $this->get('/category/monochrome')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Blog/Index')
                ->has('posts.data', 1)
            );

        $this->get('/tag/film')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Blog/Index')
                ->has('posts.data', 1)
            );
    }

    public function test_blog_detail_sanitizes_html_and_hides_drafts(): void
    {
        Post::factory()->create([
            'slug' => 'clean-story',
            'content' => '<p>Safe prose</p><script>alert("xss")</script>',
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        Post::factory()->draft()->create(['slug' => 'hidden-draft']);

        $this->get('/blog/clean-story')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Blog/Show')
                ->where('post.slug', 'clean-story')
                ->where('post.content', '<p>Safe prose</p>')
                ->has('latestPosts')
            );

        $this->get('/blog/hidden-draft')
            ->assertNotFound()
            ->assertInertia(fn (Assert $page) => $page->component('Errors/NotFound'));
    }

    public function test_gallery_and_static_pages_and_404_routes(): void
    {
        $user = User::factory()->admin()->create();
        $mediaA = Media::factory()->create(['user_id' => $user->id]);
        $mediaB = Media::factory()->create(['user_id' => $user->id]);

        $gallery = Gallery::factory()->create([
            'slug' => 'winter-tides',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        $gallery->media()->attach([
            $mediaB->id => ['sort_order' => 2, 'caption' => 'Frame 2'],
            $mediaA->id => ['sort_order' => 1, 'caption' => 'Frame 1'],
        ]);

        Gallery::factory()->draft()->create(['slug' => 'draft-gallery']);

        Page::factory()->create(['slug' => 'about', 'status' => 'published', 'title' => 'About Studio']);
        Page::factory()->create(['slug' => 'contact', 'status' => 'published', 'title' => 'Contact Studio']);

        $this->get('/gallery')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Gallery/Index')
                ->has('galleries.data', 1)
            );

        $this->get('/gallery/winter-tides')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Gallery/Show')
                ->where('gallery.media.0.caption', 'Frame 1')
                ->where('gallery.media.1.caption', 'Frame 2')
            );

        $this->get('/gallery/draft-gallery')->assertNotFound();

        $this->get('/about')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Pages/Show')->where('isContact', false));

        $this->get('/contact')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('Pages/Show')->where('isContact', true));

        $this->get('/nonexistent-editorial-route')
            ->assertNotFound()
            ->assertInertia(fn (Assert $page) => $page->component('Errors/NotFound'));
    }

    public function test_homepage_supports_projects_and_category_digest_sections(): void
    {
        $category = Category::factory()->create(['slug' => 'essays']);
        Post::factory()->create([
            'category_id' => $category->id,
            'status' => 'published',
            'published_at' => now()->subHour(),
        ]);

        HomepageSection::create([
            'title' => 'Selected Editions',
            'section_key' => 'projects',
            'type' => 'projects',
            'sort_order' => 1,
            'is_active' => true,
            'settings' => [
                'eyebrow' => 'Curated Choice',
                'items' => json_encode([
                    ['title' => 'Print Edition', 'price_label' => 'IDR', 'price_value' => '150,000.00'],
                ]),
            ],
        ]);

        HomepageSection::create([
            'title' => 'Category Digest',
            'section_key' => 'category_digest',
            'type' => 'category_digest',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Home')
                ->has('sections', 2)
                ->where('sections.0.items.0.title', 'Print Edition')
                ->has('sections.1.categories', 1)
                ->has('sections.1.posts', 1)
            );
    }
}
