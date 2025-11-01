<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPlat extends Model
{
    use HasFactory;

    protected $table = 'temp_plat';

    protected $fillable = [
        'id_user',
        'id_plat',
        'idproduit',
        'id_unite',
        'qte',
        'nombre_couvert'
    ];

    public function plat()
    {
        return $this->belongsTo(Plat::class, 'id_plat');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'idproduit');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}