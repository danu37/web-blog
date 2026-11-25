<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Gallery;
use App\Models\HomepageSection;
use App\Models\Media;
use App\Models\Page;
use App\Models\Post;
use App\Models\SiteSetting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Tests\TestCase;

class DatabaseAndPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_models_generate_valid_uuid_primary_keys(): void
    {
        $user = User::factory()->admin()->create();
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $media = Media::factory()->create(['user_id' => $user->id]);
        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'featured_image_id' => $media->id,
        ]);
        $gallery = Gallery::factory()->create();
        $page = Page::factory()->create();

        foreach ([$user, $category, $tag, $media, $post, $gallery, $page] as $model) {
            $this->assertTrue(Str::isUuid((string) $model->id), get_class($model).' should have a valid UUID primary key.');
        }
    }

    public function test_user_category_post_and_tag_relationships(): void
    {
        $user = User::factory()->editor()->create();
        $category = Category::factory()->create();
        $media = Media::factory()->create(['user_id' => $user->id]);
        $tags = Tag::factory()->count(2)->create();

        $post = Post::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'featured_image_id' => $media->id,
        ]);
        $post->tags()->attach($tags->pluck('id'));

        $this->assertTrue($user->posts->contains($post));
        $this->assertTrue($user->media->contains($media));
        $this->assertTrue($category->posts->contains($post));
        $this->assertEquals($user->id, $post->user->id);
        $this->assertEquals($category->id, $post->category->id);
        $this->assertEquals($media->id, $post->featuredImage->id);
        $this->assertCount(2, $post->tags);
        $this->assertTrue($tags->first()->posts->contains($post));
    }

    public function test_gallery_and_media_relationship_respects_sort_order_and_caption(): void
    {
        $user = User::factory()->admin()->create();
        $gallery = Gallery::factory()->create();
        $firstMedia = Media::factory()->create(['user_id' => $user->id]);
        $secondMedia = Media::factory()->create(['user_id' => $user->id]);

        $gallery->media()->attach([
            $secondMedia->id => ['sort_order' => 2, 'caption' => 'Second frame'],
            $firstMedia->id => ['sort_order' => 1, 'caption' => 'First frame'],
        ]);

        $orderedMedia = $gallery->fresh('media')->media;

        $this->assertCount(2, $orderedMedia);
        $this->assertEquals($firstMedia->id, $orderedMedia[0]->id);
        $this->assertEquals('First frame', $orderedMedia[0]->pivot->caption);
        $this->assertEquals($secondMedia->id, $orderedMedia[1]->id);
        $this->assertTrue($firstMedia->galleries->contains($gallery));
    }

    public function test_soft_deletes_work_on_posts_galleries_and_pages(): void
    {
        $post = Post::factory()->create();
        $gallery = Gallery::factory()->create();
        $page = Page::factory()->create();

        $post->delete();
        $gallery->delete();
        $page->delete();

        $this->assertSoftDeleted($post);
        $this->assertSoftDeleted($gallery);
        $this->assertSoftDeleted($page);

        $post->restore();
        $this->assertDatabaseHas('posts', ['id' => $post->id, 'deleted_at' => null]);
    }

    public function test_unique_slug_constraint_is_enforced(): void
    {
        Post::factory()->create(['slug' => 'unique-editorial-slug']);

        $this->expectException(QueryException::class);

        Post::factory()->create(['slug' => 'unique-editorial-slug']);
    }

    public function test_foreign_key_constraints_are_enforced(): void
    {
        $category = Category::factory()->create();
        $post = Post::factory()->create(['category_id' => $category->id]);

        $category->delete();
        $this->assertNull($post->fresh()->category_id);

        $this->expectException(QueryException::class);

        Post::factory()->create(['user_id' => (string) Str::uuid()]);
    }

    public function test_role_based_policies_enforce_admin_editor_and_reader_permissions(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->editor()->create();
        $reader = User::factory()->reader()->create();

        $post = Post::factory()->create(['user_id' => $editor->id]);
        $category = Category::factory()->create();
        $tag = Tag::factory()->create();
        $media = Media::factory()->create(['user_id' => $editor->id]);
        $gallery = Gallery::factory()->create();
        $page = Page::factory()->create();
        $setting = SiteSetting::create(['key' => 'test_key', 'value' => 'val', 'type' => 'text']);
        $section = HomepageSection::create([
            'title' => 'Hero',
            'section_key' => 'hero_test',
            'type' => 'hero',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // Admin has full access
        $this->assertTrue(Gate::forUser($admin)->allows('create', Post::class));
        $this->assertTrue(Gate::forUser($admin)->allows('forceDelete', $post));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $setting));
        $this->assertTrue(Gate::forUser($admin)->allows('update', $section));

        // Editor can manage editorial content, but not settings, homepage sections, or forceDelete
        $this->assertTrue(Gate::forUser($editor)->allows('create', Post::class));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $post));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $category));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $tag));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $media));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $gallery));
        $this->assertTrue(Gate::forUser($editor)->allows('update', $page));
        $this->assertFalse(Gate::forUser($editor)->allows('forceDelete', $post));
        $this->assertFalse(Gate::forUser($editor)->allows('update', $setting));
        $this->assertFalse(Gate::forUser($editor)->allows('update', $section));

        // Reader has no CMS access
        $this->assertFalse(Gate::forUser($reader)->allows('viewAny', Post::class));
        $this->assertFalse(Gate::forUser($reader)->allows('create', Media::class));
        $this->assertFalse(Gate::forUser($reader)->allows('viewAny', Gallery::class));
        $this->assertFalse(Gate::forUser($reader)->allows('viewAny', Page::class));
    }
}
