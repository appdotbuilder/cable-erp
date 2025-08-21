<?php

namespace Database\Factories;

use App\Models\Cable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cable>
 */
class CableFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Cable>
     */
    protected $model = Cable::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['power', 'data', 'coaxial', 'fiber_optic', 'control'];
        $sizes = ['12 AWG', '14 AWG', '16 AWG', '18 AWG', 'Cat5e', 'Cat6', 'Cat6a', 'RG-6', 'RG-11', 'SM 9/125', 'MM 50/125'];
        $manufacturers = ['Belden', 'CommScope', 'General Cable', 'Southwire', 'Panduit', 'Nexans'];
        $units = ['meters', 'feet', 'rolls', 'boxes'];

        return [
            'barcode' => $this->faker->unique()->numerify('CBL-####-####'),
            'name' => $this->faker->words(3, true) . ' Cable',
            'size' => $this->faker->randomElement($sizes),
            'type' => $this->faker->randomElement($types),
            'description' => $this->faker->sentence(10),
            'manufacturer' => $this->faker->randomElement($manufacturers),
            'unit_price' => $this->faker->randomFloat(2, 5, 500),
            'stock_quantity' => $this->faker->numberBetween(0, 1000),
            'minimum_stock' => $this->faker->numberBetween(5, 50),
            'unit_of_measure' => $this->faker->randomElement($units),
            'location' => 'Warehouse-' . $this->faker->randomElement(['A', 'B', 'C']) . '-' . $this->faker->numberBetween(1, 20),
            'status' => $this->faker->randomElement(['active', 'discontinued']),
        ];
    }

    /**
     * Indicate that the cable is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    /**
     * Indicate that the cable has low stock.
     */
    public function lowStock(): static
    {
        return $this->state(function (array $attributes) {
            $minStock = $this->faker->numberBetween(10, 30);
            return [
                'minimum_stock' => $minStock,
                'stock_quantity' => $this->faker->numberBetween(0, $minStock - 1),
            ];
        });
    }
}