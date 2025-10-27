<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'date',
        'entree',
        'sortie',
        'reste',
        'prix_unitaire', // Added new field
        'id_achat',
        'id_vente',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'entree' => 'decimal:2',
        'sortie' => 'decimal:2',
        'reste' => 'decimal:2',
        'prix_unitaire' => 'decimal:2', // Added new field cast
    ];

    /**
     * Get the product that owns the inventory record.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the purchase that generated this inventory entry.
     */
    public function achat(): BelongsTo
    {
        return $this->belongsTo(Achat::class, 'id_achat');
    }

    /**
     * Get the sale that generated this inventory entry.
     */
    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class, 'id_vente');
    }

    /**
     * Get the user who created this inventory entry.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}