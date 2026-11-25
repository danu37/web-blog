<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    public function definition(): array
    {
        $fileName = fake()->unique()->slug(2).'.jpg';

        return [
            'user_id' => User::factory(),
            'disk' => 'public',
            'file_name' => $fileName,
            'file_path' => 'media/2026/11/'.$fileName,
            'mime_type' => 'image/jpeg',
            'file_size' => fake()->numberBetween(80_000, 1_800_000),
            'alt_text' => fake()->sentence(6),
        ];
    }
}
