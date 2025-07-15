<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Driver>
 */
class DriverFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'image' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/300/300',
            'location_id' => Location::inRandomOrder()->value('id') ?? Location::factory(),
        ];
    }
}
