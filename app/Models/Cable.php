<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Cable
 *
 * @property int $id
 * @property string $barcode
 * @property string $name
 * @property string $size
 * @property string $type
 * @property string|null $description
 * @property string|null $manufacturer
 * @property float $unit_price
 * @property int $stock_quantity
 * @property int $minimum_stock
 * @property string $unit_of_measure
 * @property string|null $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StockMovement> $stockMovements
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InvoiceItem> $invoiceItems
 * @property-read bool $is_low_stock
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Cable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Cable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereMinimumStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereUnitOfMeasure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Cable active()
 * @method static \Illuminate\Database\Eloquent\Builder|Cable lowStock()
 * @method static \Database\Factories\CableFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Cable extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'barcode',
        'name',
        'size',
        'type',
        'description',
        'manufacturer',
        'unit_price',
        'stock_quantity',
        'minimum_stock',
        'unit_of_measure',
        'location',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'minimum_stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cables';

    /**
     * Get the stock movements for the cable.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get the invoice items for the cable.
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Scope a query to only include active cables.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include cables with low stock.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= minimum_stock');
    }

    /**
     * Check if cable stock is low.
     *
     * @return bool
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock_quantity <= $this->minimum_stock;
    }
}