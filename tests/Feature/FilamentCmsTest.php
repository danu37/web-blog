<?php

namespace Tests\Feature;

use App\Models\Gallery;
use App\Models\Media;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilamentCmsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_users_are_redirected_from_admin_to_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/login')->assertOk();
    }

    public function test_panel_access_is_restricted_to_admin_and_editor(): void
    {
        $admin = User::factory()->admin()->create();
        $editor = User::factory()->editor()->create();
        $reader = User::factory()->reader()->create();

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($editor)->get('/admin')->assertOk();
        $this->actingAs($reader)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_access_all_cms_resources(): void
    {
        $admin = User::factory()->admin()->create();

        $routes = [
            '/admin/posts',
            '/admin/categories',
            '/admin/tags',
            '/admin/media',
            '/admin/galleries',
            '/admin/pages',
            '/admin/homepage-sections',
            '/admin/site-settings',
            '/admin/users',
        ];

        foreach ($routes as $route) {
            $this->actingAs($admin)->get($route)->assertOk();
        }
    }

    public function test_editor_can_access_editorial_resources_but_not_admin_only_resources(): void
    {
        $editor = User::factory()->editor()->create();

        foreach (['/admin/posts', '/admin/categories', '/admin/tags', '/admin/media', '/admin/galleries', '/admin/pages'] as $route) {
            $this->actingAs($editor)->get($route)->assertOk();
        }

        foreach (['/admin/homepage-sections', '/admin/site-settings', '/admin/users'] as $route) {
            $this->actingAs($editor)->get($route)->assertForbidden();
        }
    }

    public function test_referenced_media_cannot_be_deleted_while_unused_media_can(): void
    {
        Storage::fake('public');

        $user = User::factory()->admin()->create();
        $usedInPost = Media::factory()->create(['user_id' => $user->id]);
        Post::factory()->create(['user_id' => $user->id, 'featured_image_id' => $usedInPost->id]);

        $usedInGallery = Media::factory()->create(['user_id' => $user->id]);
        $gallery = Gallery::factory()->create();
        $gallery->media()->attach($usedInGallery->id, ['sort_order' => 1]);

        $unused = Media::factory()->create([
            'user_id' => $user->id,
            'file_path' => 'media/2026/11/unused.jpg',
        ]);
        Storage::disk('public')->put('media/2026/11/unused.jpg', 'img');

        $this->assertFalse($usedInPost->delete());
        $this->assertDatabaseHas('media', ['id' => $usedInPost->id]);

        $this->assertFalse($usedInGallery->delete());
        $this->assertDatabaseHas('media', ['id' => $usedInGallery->id]);

        $this->assertTrue($unused->delete());
        $this->assertDatabaseMissing('media', ['id' => $unused->id]);
        Storage::disk('public')->assertMissing('media/2026/11/unused.jpg');
    }
}
