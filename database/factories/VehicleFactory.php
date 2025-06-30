<?php

namespace Database\Factories;

use App\Models\Vehicle;
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

    protected $model = Vehicle::class; // Sesuaikan dengan model mobil Anda

    // Daftar merek dan model mobil (contoh untuk pasar Indonesia)
    protected $car_makes_and_models = [
        'Honda' => ['Civic', 'CR-V', 'HR-V', 'Brio', 'Jazz', 'Accord', 'Mobilio', 'BR-V'],
        'Toyota' => ['Avanza', 'Innova', 'Fortuner', 'Rush', 'Yaris', 'Camry', 'Agya', 'Calya', 'Raize', 'Vios', 'Corolla Altis'],
        'Suzuki' => ['Ertiga', 'XL7', 'Baleno', 'Ignis', 'Jimny', 'Carry Pick-up', 'APV'],
        'Mitsubishi' => ['Pajero Sport', 'Xpander', 'Outlander Sport', 'Triton', 'L300'],
        'Daihatsu' => ['Xenia', 'Terios', 'Ayla', 'Sigra', 'Rocky', 'Luxio', 'Gran Max'],
        'Nissan' => ['Livina', 'X-Trail', 'Serena', 'Kicks', 'Magnite'],
        'Mazda' => ['Mazda 2', 'Mazda 3', 'CX-3', 'CX-5', 'CX-30'],
        'Hyundai' => ['Creta', 'Palisade', 'Santa Fe', 'Staria', 'Stargazer', 'Ioniq 5'],
        'Kia' => ['Seltos', 'Sonet', 'Carnival', 'Picanto'],
        'Wuling' => ['Almaz', 'Cortez', 'Confero', 'Air EV'],
    ];

    public function definition(): array
    {
        $available_makes = array_keys($this->car_makes_and_models); // isinya array dari car_makes_and_models
        $random_make = $this->faker->randomElement($available_makes); // ngambil angka acak di dalem scope available_makes 

        // 2. Dari merek yang terpilih, pilih modelnya secara acak
        $models_for_make = $this->car_makes_and_models[$random_make]; // ngestore 1 array yg dipilih
        $random_model = $this->faker->randomElement($models_for_make); // ngerandom nama mobil dari array yang dipilih

        return [
            'name' => $random_make . ' ' . $random_model, // Menggabungkan merek dan model
            'price' => $this->faker->numberBetween(1000000, 9000000),
            'year' => $this->faker->year(),
            'image' => 'vehicle_assets/meme.jpg',
            'engine_cc' => $this->faker->numberBetween(1000,5000),
            'transmission_type' => $this->faker->randomElement(['Matic', 'Manual']),
            'type' => 'Mobil',
            'vehicle_category' => $this->faker->randomElement(['SUV', 'MPV', 'City Car', 'Sedan', 'Pickup', 'Van / Minibus', 'Listrik']),
            'vehicle_location' => $this->faker->randomElement(['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Utara', 'Jakarta Selatan', 'Jakarta Timur'])
        ];
    }
}
