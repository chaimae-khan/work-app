<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Vente extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ventes';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'total',
        'status',
        'type_commande', 
        'type_menu',
        'id_client',
        'id_formateur', 
        'id_user',
        'is_transfer',
        'eleves',
        'personnel',
        'invites',
        'divers',
        'date_usage',
        'entree',
        'plat_principal',
        'accompagnement',
        'dessert',
    ];

    /**
     * Attributes to exclude from the Audit.
     *
     * @var array
     */
    protected $auditExclude = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total' => 'decimal:2',
        'eleves' => 'integer',
        'personnel' => 'integer',
        'invites' => 'integer',
        'divers' => 'integer',
        'date_usage' => 'date',
    ];

    /**
     * Set attribute and convert empty strings to null for menu fields
     */
    public function setAttribute($key, $value)
    {
        $menuFields = ['entree', 'plat_principal', 'accompagnement', 'dessert'];
        
        if (in_array($key, $menuFields) && empty($value)) {
            $value = null;
        }
        
        return parent::setAttribute($key, $value);
    }

    /**
     * Get the client associated with the vente.
     */
    public function client()
    {
        return $this->belongsTo(Client::class, 'id_client');
    }

    /**
     * Get the formateur associated with the vente.
     */
    public function formateur()
    {
        return $this->belongsTo(User::class, 'id_formateur');
    }

    /**
     * Get the user who created the vente.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the ligne_ventes for the vente.
     */
    public function ligneVentes()
    {
        return $this->hasMany(LigneVente::class, 'idvente');
    }
}