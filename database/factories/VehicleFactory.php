<?php

namespace Database\Factories;

use App\Models\VehicleType;
use App\Models\VehicleName;
use App\Models\VehicleTransmission;
use App\Models\Location;
use App\Models\Vehicle;
use App\Models\VehicleImage;
use App\Models\VehicleCategory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

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
    protected $model = Vehicle::class;

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

    // Daftar merek dan model motor (contoh untuk pasar Indonesia)
    protected $motorcycle_makes_and_models = [
        'Honda' => ['PCX', 'Vario', 'Beat', 'Scoopy', 'CBR', 'CB150R', 'CRF'],
        'Yamaha' => ['NMAX', 'Aerox', 'Mio', 'Fino', 'Vixion', 'R15', 'Lexi'],
        'Kawasaki' => ['Ninja', 'KLX', 'W175', 'D-Tracker', 'Versys'],
        'Suzuki' => ['Satria FU', 'GSX-R150', 'GSX-S150', 'Address', 'Nex'],
        'Vespa' => ['Primavera', 'Sprint', 'GTS', 'LX'],
    ];

    // Mapping model motor ke jenis transmisi yang lebih spesifik
    protected $specific_motorcycle_transmissions = [
        'Honda PCX' => 'Automatic',
        'Honda Vario' => 'Automatic',
        'Honda Beat' => 'Automatic',
        'Honda Scoopy' => 'Automatic',
        'Honda CBR' => 'Manual',
        'Honda CB150R' => 'Manual',
        'Honda CRF' => 'Manual',
        'Yamaha NMAX' => 'Automatic',
        'Yamaha Aerox' => 'Automatic',
        'Yamaha Mio' => 'Automatic',
        'Yamaha Fino' => 'Automatic',
        'Yamaha Vixion' => 'Clutch',
        'Yamaha R15' => 'Clutch',
        'Yamaha Lexi' => 'Automatic',
        'Kawasaki Ninja' => 'Clutch',
        'Kawasaki KLX' => 'Clutch',
        'Kawasaki W175' => 'Clutch',
        'Kawasaki D-Tracker' => 'Clutch',
        'Kawasaki Versys' => 'Clutch',
        'Suzuki Satria FU' => 'Clutch',
        'Suzuki GSX-R150' => 'Clutch',
        'Suzuki GSX-S150' => 'Clutch',
        'Suzuki Address' => 'Automatic',
        'Suzuki Nex' => 'Automatic',
        'Vespa Primavera' => 'Automatic',
        'Vespa Sprint' => 'Automatic',
        'Vespa GTS' => 'Automatic',
        'Vespa LX' => 'Automatic',
    ];

    // Mapping model mobil ke jenis transmisi yang lebih spesifik
    protected $specific_car_transmissions = [
        'Honda Civic' => 'Automatic',
        'Honda CR-V' => 'Automatic',
        'Toyota Innova' => 'Manual',
        'Toyota Fortuner' => 'Automatic',
        'Mitsubishi Pajero Sport' => 'Automatic',
        'Toyota Avanza' => 'Manual',
    ];

    public function definition(): array
    {
        // Secara default, kita akan membuat kendaraan acak
        $type = $this->faker->randomElement([1, 2]); // 1 untuk mobil, 2 untuk motor

        return $this->attributesForType($type);
    }

    /**
     * Tentukan state khusus untuk mobil.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function car()
    {
        return $this->state(fn (array $attributes) => $this->attributesForType(1));
    }

    /**
     * Tentukan state khusus untuk motor.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function motorcycle()
    {
        return $this->state(fn (array $attributes) => $this->attributesForType(2));
    }
    
    /**
     * Tentukan atribut berdasarkan tipe kendaraan.
     *
     * @param int $type
     * @return array
     */
    protected function attributesForType(int $type): array
    {   
        // $carImages = [
        //     'honda-civic.jpg',
        //     'toyota-avanza.png',
        //     'mitsubishi-xpander.jpg',
        // ];

        // $motorcycleImages = [
        //     'honda-beat.jpg',
        //     'yamaha-nmax.png',
        //     'kawasaki-ninja.jpg',
        // ];

        if ($type === 1) { // Mobil
            $make = $this->faker->randomElement(array_keys($this->car_makes_and_models));
            $model = $this->faker->randomElement($this->car_makes_and_models[$make]);
            $vehicleName = VehicleName::firstOrCreate(['name' => "$make $model"]);
            
       
            $fullModelName = "$make $model";
            $transmissionName = $this->specific_car_transmissions[$fullModelName] ?? $this->faker->randomElement(['Manual', 'Automatic']);
            
         
            $transmissionId = VehicleTransmission::where('transmission', $transmissionName)->value('id')
                ?? VehicleTransmission::inRandomOrder()->value('id');

            return [
                'vehicle_type_id' => $type,
                'vehicle_name_id' => $vehicleName->id,
                'vehicle_transmission_id' => $transmissionId,
                'engine_cc' => $this->faker->numberBetween(1000, 5000),
                'seats' => $this->faker->numberBetween(4, 8),
                'year' => $this->faker->year(),
                'location_id' => Location::inRandomOrder()->value('id'),
                'main_image' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/700/400',
                'price' => $this->faker->numberBetween(100000, 500000),
            ];
        } else { // Motor
            $make = $this->faker->randomElement(array_keys($this->motorcycle_makes_and_models));
            $model = $this->faker->randomElement($this->motorcycle_makes_and_models[$make]);
            $vehicleName = VehicleName::firstOrCreate(['name' => "$make $model"]);
            
      
            $fullModelName = "$make $model";
            $transmissionName = $this->specific_motorcycle_transmissions[$fullModelName] ?? $this->faker->randomElement(['Manual', 'Automatic', 'Clutch']);
            
            $transmissionId = VehicleTransmission::where('transmission', $transmissionName)->value('id')
                ?? VehicleTransmission::inRandomOrder()->value('id');

            $imageNames = [
            'mobil-bmw.jpg',
            'mobil-honda.jpg',
            'mobil-toyota.jpg',
            ];

            return [
                'vehicle_type_id' => $type,
                'vehicle_name_id' => $vehicleName->id,
                'vehicle_transmission_id' => $transmissionId,
                'engine_cc' => $this->faker->numberBetween(50, 1000),
                'seats' => 2,
                'year' => $this->faker->year(),
                'location_id' => Location::inRandomOrder()->value('id'),
                'main_image' => 'https://picsum.photos/seed/' . $this->faker->uuid . '/700/400',
                'price' => $this->faker->numberBetween(10000, 100000),
            ];
        }
    }

    public function configure()
    {
        return $this->afterCreating(function (Vehicle $vehicle) {
            VehicleImage::factory(4)->create(['vehicle_id' => $vehicle->id]);
            $category_ids = VehicleCategory::inRandomOrder()->limit(2)->pluck('id');
            $vehicle->vehicleCategories()->attach($category_ids);
        });
    }
}
