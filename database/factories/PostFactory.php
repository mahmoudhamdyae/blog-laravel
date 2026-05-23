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
        static $fakerAr = null;
        if ($fakerAr === null) {
            $fakerAr = \Faker\Factory::create('ar_JO');
        }

        return [
            'title' => $this->faker->sentence(),
            'title_ar' => rtrim($fakerAr->realText(30), '.'),
            'description' => $this->faker->paragraph(),
            'description_ar' => $fakerAr->realText(250),
            'user_id' => $this->faker->numberBetween(1, 50),
            // 'image' => $this->faker->imageUrl(640, 480, 'posts', true),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
