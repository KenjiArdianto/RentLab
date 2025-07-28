<?php

namespace Database\Factories;

use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleTransmission;
use App\Models\Location;
use App\Models\VehicleImage;
use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = VehicleType::inRandomOrder()->value('id');

        $name = match ($type) {
            1 => VehicleName::whereBetween('id', [1,10])->inRandomOrder()->value('id'),
            2 => VehicleName::whereBetween('id', [11,20])->inRandomOrder()->value('id'),
        };

        $transmission = match($type) {
            1 => VehicleTransmission::whereBetween('id', [1,2])->inRandomOrder()->value('id'),
            2 => VehicleTransmission::inRandomOrder()->value('id'),
        };
    
        $engine_cc = match($type) {
            1 => $this->faker->numberBetween(1000,5000),
            2 => $this->faker->numberBetween(50,2500),
        };

        return [
            'vehicle_type_id' => $type,
            'vehicle_name_id' => $name,
            'vehicle_transmission_id' => $transmission,
            'engine_cc' => $engine_cc,
            'seats' => $this->faker->numberBetween(2,8),
            'year' => $this->faker->year(),
            'location_id' => Location::inRandomOrder()->value('id'),
            'main_image' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/700/400',
            'price' => $this->faker->numberBetween(100000,500000),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function ($vehicle) {
            VehicleImage::factory(4)->create(['vehicle_id' => $vehicle->id]);
            $category_ids = VehicleCategory::inRandomOrder()->limit(2)->pluck('id');
            $vehicle->vehicleCategories()->attach($category_ids);
        });
    }
}
