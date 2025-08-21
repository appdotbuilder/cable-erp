<?php

namespace Database\Factories;

use App\Models\Cable;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockMovement>
 */
class StockMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\StockMovement>
     */
    protected $model = StockMovement::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['in', 'out', 'adjustment'];
        $type = $this->faker->randomElement($types);
        $previousStock = $this->faker->numberBetween(0, 500);
        $quantity = $this->faker->numberBetween(1, 100);
        
        if ($type === 'out') {
            $quantity = -$quantity;
        }
        
        $currentStock = max(0, $previousStock + $quantity);

        return [
            'cable_id' => Cable::factory(),
            'user_id' => User::factory(),
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previousStock,
            'current_stock' => $currentStock,
            'reference_number' => $this->faker->optional()->numerify('REF-####'),
            'notes' => $this->faker->optional()->sentence(),
            'movement_date' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Indicate that the movement is stock in.
     */
    public function stockIn(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = abs($attributes['quantity']);
            return [
                'type' => 'in',
                'quantity' => $quantity,
                'current_stock' => $attributes['previous_stock'] + $quantity,
                'notes' => 'Stock replenishment',
            ];
        });
    }

    /**
     * Indicate that the movement is stock out.
     */
    public function stockOut(): static
    {
        return $this->state(function (array $attributes) {
            $quantity = -abs($attributes['quantity']);
            return [
                'type' => 'out',
                'quantity' => $quantity,
                'current_stock' => max(0, $attributes['previous_stock'] + $quantity),
                'notes' => 'Sale/Usage',
            ];
        });
    }
}