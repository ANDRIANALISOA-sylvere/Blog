<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
        $title = fake()->unique()->sentence();
        return [
            'title'=> $title,
            'slug'=> Str::slug($title),
            'content' => fake()->paragraphs(asText:true),
            'image' => fake()->imageUrl(640, 480, 'posts', true),
            'user_id'=> User::inRandomOrder()->first()->id,
            'category_id'=> Category::inRandomOrder()->first()->id
        ];
    }
}
