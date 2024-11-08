<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $appUrl = env('APP_URL');
        $images = [
            "$appUrl/assets/image/follow-us.png",
            "$appUrl/assets/image/new-post.png",
        ];

        return [
            'name'      => $this->faker->title(),
            'image'      => $images[rand(0, 1)],
            'desc'      => $this->faker->text(),
        ];
    }
}
