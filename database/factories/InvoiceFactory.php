<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Invoice>
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invoiceDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $dueDate = (clone $invoiceDate)->modify('+30 days');
        $subtotal = $this->faker->randomFloat(2, 100, 10000);
        $taxAmount = $subtotal * 0.1; // 10% tax
        $totalAmount = $subtotal + $taxAmount;
        $paidAmount = $this->faker->boolean(70) ? $totalAmount : $this->faker->randomFloat(2, 0, $totalAmount);
        $outstandingAmount = $totalAmount - $paidAmount;
        
        $status = 'draft';
        if ($paidAmount >= $totalAmount) {
            $status = 'paid';
        } elseif ($dueDate < now() && $outstandingAmount > 0) {
            $status = 'overdue';
        } elseif ($paidAmount > 0) {
            $status = 'sent';
        }

        return [
            'invoice_number' => 'INV-' . $this->faker->unique()->numerify('####-####'),
            'customer_id' => Customer::factory(),
            'user_id' => User::factory(),
            'invoice_date' => $invoiceDate,
            'due_date' => $dueDate,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'outstanding_amount' => $outstandingAmount,
            'status' => $status,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    /**
     * Indicate that the invoice is paid.
     */
    public function paid(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'paid_amount' => $attributes['total_amount'],
                'outstanding_amount' => 0,
                'status' => 'paid',
            ];
        });
    }

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'due_date' => $this->faker->dateTimeBetween('-60 days', '-1 day'),
                'paid_amount' => 0,
                'outstanding_amount' => $attributes['total_amount'],
                'status' => 'overdue',
            ];
        });
    }
}