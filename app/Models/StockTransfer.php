<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class StockTransfer extends Model implements Auditable
{
    use HasFactory;
    use SoftDeletes;
    use AuditableTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stocktransfer';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_user',
        'status',
        'from',
        'to',
        'refusal_reason'
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
        'from' => 'integer',
        'to' => 'integer',
        'id_user' => 'integer',
    ];

    /**
     * Status constants for better maintainability
     */
    const STATUS_CREATION = 'Création';
    const STATUS_VALIDATION = 'Validation';
    const STATUS_REFUS = 'Refus';

    /**
     * Get all available statuses
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_CREATION => 'Création',
            self::STATUS_VALIDATION => 'Validation',
            self::STATUS_REFUS => 'Refus',
        ];
    }

    /**
     * Check if the status is refused
     *
     * @return bool
     */
    public function isRefused(): bool
    {
        return $this->status === self::STATUS_REFUS;
    }

    /**
     * Check if the status is validated
     *
     * @return bool
     */
    public function isValidated(): bool
    {
        return $this->status === self::STATUS_VALIDATION;
    }

    /**
     * Check if the status is in creation
     *
     * @return bool
     */
    public function isInCreation(): bool
    {
        return $this->status === self::STATUS_CREATION;
    }

    /**
     * Get the status badge HTML for display
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        $statusColors = [
            self::STATUS_CREATION => 'bg-warning',
            self::STATUS_VALIDATION => 'bg-success',
            self::STATUS_REFUS => 'bg-danger',
        ];

        $color = $statusColors[$this->status] ?? 'bg-secondary';
        $statusHtml = '<span class="badge ' . $color . '">' . $this->status . '</span>';
        
        // Add refusal reason if status is "Refus" and reason exists
        if ($this->isRefused() && !empty($this->refusal_reason)) {
            $statusHtml .= '<br><small class="text-muted mt-1 d-block">' .
                          '<i class="fa-solid fa-info-circle me-1"></i>' . 
                          htmlspecialchars($this->refusal_reason) . 
                          '</small>';
        }
        
        return $statusHtml;
    }

    /**
     * Custom audit tags for better tracking
     *
     * @return array
     */
    public function generateTags(): array
    {
        $tags = ['stock-transfer'];
        
        // Add specific tags based on the type of operation
        if ($this->from && $this->to) {
            $tags[] = 'transfer'; // Both from and to are set
        } elseif (!$this->from && $this->to) {
            $tags[] = 'retour'; // Only to is set (from is null)
        }
        
        // Add status tag
        if ($this->status) {
            $tags[] = strtolower($this->status);
        }
        
        return $tags;
    }

    /**
     * Transform the audit data for better readability
     * FIXED: Removed the context field that was causing database issues
     *
     * @return array
     */
    public function transformAudit(array $data): array
    {
        // Add human-readable labels for status changes
        if (isset($data['new_values']['status']) || isset($data['old_values']['status'])) {
            $statusLabels = [
                'Création' => 'Creation',
                'Validation' => 'Validated',
                'Refus' => 'Refused',
                'Livraison' => 'Delivered',
                'Réception' => 'Received'
            ];
            
            if (isset($data['new_values']['status'])) {
                $data['new_values']['status_label'] = $statusLabels[$data['new_values']['status']] ?? $data['new_values']['status'];
            }
            
            if (isset($data['old_values']['status'])) {
                $data['old_values']['status_label'] = $statusLabels[$data['old_values']['status']] ?? $data['old_values']['status'];
            }
        }
        
        // REMOVED: The context field that was causing the database error
        // if (isset($data['new_values']['refusal_reason']) || isset($data['old_values']['refusal_reason'])) {
        //     $data['context'] = 'refusal_reason_change';
        // }
        
        return $data;
    }

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();
        
        // Automatically clear refusal_reason when status is not 'Refus'
        static::saving(function ($model) {
            if ($model->status !== self::STATUS_REFUS) {
                $model->refusal_reason = null;
            }
        });
    }

    /**
     * Get the user who created the transfer.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the user who is sending (from).
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from');
    }

    /**
     * Get the user who is receiving (to).
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to');
    }

    /**
     * Get the line transfers associated with this stock transfer.
     */
    public function lineTransfers()
    {
        return $this->hasMany(LineTransfer::class, 'id_stocktransfer');
    }

    /**
     * Scope to get only Transfer records (both from and to are not null).
     */
    public function scopeTransfers($query)
    {
        return $query->whereNotNull('from')->whereNotNull('to');
    }

    /**
     * Scope to get only Retour records (from is null).
     */
    public function scopeRetours($query)
    {
        return $query->whereNull('from')->whereNotNull('to');
    }

    /**
     * Scope to get refused records
     */
    public function scopeRefused($query)
    {
        return $query->where('status', self::STATUS_REFUS);
    }

    /**
     * Scope to get validated records
     */
    public function scopeValidated($query)
    {
        return $query->where('status', self::STATUS_VALIDATION);
    }

    /**
     * Scope to get records in creation
     */
    public function scopeInCreation($query)
    {
        return $query->where('status', self::STATUS_CREATION);
    }

    /**
     * Check if this is a Transfer operation.
     *
     * @return bool
     */
    public function isTransfer(): bool
    {
        return !is_null($this->from) && !is_null($this->to);
    }

    /**
     * Check if this is a Retour operation.
     *
     * @return bool
     */
    public function isRetour(): bool
    {
        return is_null($this->from) && !is_null($this->to);
    }

    /**
     * Get the type of operation (Transfer or Retour).
     *
     * @return string
     */
    public function getOperationType(): string
    {
        if ($this->isTransfer()) {
            return 'Transfer';
        } elseif ($this->isRetour()) {
            return 'Retour';
        }
        
        return 'Unknown';
    }
}