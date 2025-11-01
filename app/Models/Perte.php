<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perte extends Model  // Changed from Pertes to Perte
{
    use HasFactory, SoftDeletes;

    protected $table = 'pertes';

    protected $fillable = [
        'id_product',
        'id_category',
        'id_subcategorie',
        'id_unite',
        'classe',
        'designation',
        'quantite',
        'nature',
        'date_perte',
        'cause',
        'status',
        'refusal_reason',
        'id_user',
    ];

    protected $casts = [
        'date_perte' => 'date',
        'quantite' => 'decimal:2',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id_category');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'id_subcategorie');
    }

    public function unite()
    {
        return $this->belongsTo(Unite::class, 'id_unite');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Status badge helper
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'En attente' => '<span class="badge bg-warning text-dark"><i class="fa-solid fa-clock"></i> En attente</span>',
            'Validé' => '<span class="badge bg-success"><i class="fa-solid fa-check"></i> Validé</span>',
            'Refusé' => '<span class="badge bg-danger"><i class="fa-solid fa-times"></i> Refusé</span>',
        ];

        return $badges[$this->status] ?? $this->status;
    }
}