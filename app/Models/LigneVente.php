<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LigneVente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ligne_vente';

    protected $fillable = [
        'id_user',
        'idvente',
        'idproduit',
        'qte',
        'contete_formateur',
        'contente_transfert',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * Relationship with Vente
     */
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'idvente');
    }

    /**
     * Relationship with Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'idproduit');
    }

    /**
     * Relationship with User (who created this line)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the total price for this line item
     */
    public function getTotalAttribute()
    {
        return $this->qte * $this->product->price_achat;
    }

    /**
     * Scope to get lines for a specific vente
     */
    public function scopeForVente($query, $venteId)
    {
        return $query->where('idvente', $venteId);
    }

    /**
     * Scope to get lines for a specific product
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('idproduit', $productId);
    }
}