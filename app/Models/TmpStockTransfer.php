<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class TmpStockTransfer extends Model
{
    use HasFactory;
    // use SoftDeletes;

    protected $table = 'tmpstocktransfer';

    protected $fillable = [
        'id_product',
        'quantite_stock',
        'quantite_transfer',
        'from', // Added new field for formateur
        'to',
        'iduser',
        'idcommande'
    ];

    /**
     * Get the user who created the temporary vente.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }
    public function command()
    {
        return $this->belongsTo(Vente::class, 'idcommande');
    }

    /**
     * Get the product associated with the temporary vente.
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produit');
    }

    /**
     * Get the client associated with the temporary vente.
     */
    public function From()
    {
        return $this->belongsTo(Client::class, 'from');
    }

    /**
     * Get the formateur associated with the temporary vente.
     */
    public function to()
    {
        return $this->belongsTo(User::class, 'to');
    }
}
