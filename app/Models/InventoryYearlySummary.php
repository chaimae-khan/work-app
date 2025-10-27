<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryYearlySummary extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'year',
        'total_entrees',
        'total_sorties',
        'end_stock',
        'average_price', // Added this line
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'total_entrees' => 'decimal:2',
        'total_sorties' => 'decimal:2',
        'end_stock' => 'decimal:2',
        'average_price' => 'decimal:2', // Added this line
    ];

    /**
     * Get the product that this summary belongs to.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}