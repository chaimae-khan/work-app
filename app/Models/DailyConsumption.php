<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DailyConsumption extends Model
{
    protected $table = 'daily_consumption';
    
    protected $fillable = [
        'consumption_date',
        'vente_id',
        'achat_id',
        'type_commande',
        'type_menu',
        'total_people',
        'total_cost',
        'total_tva',
        'average_cost_per_person',
        'category_costs', // Added category_costs
        'eleves',
        'personnel',
        'invites',
        'divers',
        'type',
    ];

    protected $casts = [
        'consumption_date' => 'date',
        'total_people' => 'integer',
        'total_cost' => 'decimal:2',
        'total_tva' => 'decimal:2',
        'average_cost_per_person' => 'decimal:2',
        'category_costs' => 'array', // Cast category_costs as array
        'eleves' => 'integer',
        'personnel' => 'integer',
        'invites' => 'integer',
        'divers' => 'integer',
    ];

    public function vente(): BelongsTo
    {
        return $this->belongsTo(Vente::class);
    }

    public function achat(): BelongsTo
    {
        return $this->belongsTo(Achat::class);
    }

    public function productDetails(): HasMany
    {
        return $this->hasMany(ConsumptionProductDetail::class, 'consumption_id');
    }
}

class ConsumptionProductDetail extends Model
{
    protected $table = 'consumption_product_details';
    
    protected $fillable = [
        'consumption_id',
        'product_id',
        'ligne_vente_id',
        'ligne_achat_id',
        'quantity',
        'unit_price',
        'tva_rate',
        'tva_amount',
        'total_price',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'tva_rate' => 'decimal:2',
        'tva_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function consumption(): BelongsTo
    {
        return $this->belongsTo(DailyConsumption::class, 'consumption_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function ligneVente(): BelongsTo
    {
        return $this->belongsTo(LigneVente::class, 'ligne_vente_id');
    }

    public function ligneAchat(): BelongsTo
    {
        return $this->belongsTo(LigneAchat::class, 'ligne_achat_id');
    }
}