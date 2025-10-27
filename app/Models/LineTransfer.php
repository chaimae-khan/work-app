<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LineTransfer extends Model
{
    use HasFactory;

    protected $table = 'line_transfer';

    protected $fillable = [
        'id_user',
        'id_product',
        'id_tva',
        'id_unite',
        'idcommande', 
        'id_stocktransfer',
        'quantite'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function tva()
    {
        return $this->belongsTo(Tva::class, 'id_tva');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite');
    }
   
    // Updated to use 'idcommande' instead of 'idvente'
    public function vente()
    {
        return $this->belongsTo(Vente::class, 'idcommande');
    }

    // New relationship with StockTransfer
    public function stockTransfer()
    {
        return $this->belongsTo(StockTransfer::class, 'id_stocktransfer');
    }
}