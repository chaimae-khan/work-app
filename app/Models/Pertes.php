<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pertes extends Model
{
    protected $table = 'pertes';
     protected $fillable = [
      'id_category', 'id_sub_categories', 'class', 'id_product', 'id_unite', 'nature', 'qte', 'date', 'cause'
    ];
}
