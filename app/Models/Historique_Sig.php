<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historique_Sig extends Model
{
    protected $table ='hostorique_sig';
    protected $fillable = ['signature', 'iduser', 'idvente','status'];
}
