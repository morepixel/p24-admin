<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'reports';

    public $timestamps = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    // Status-Konstanten
    const STATUS_NEW = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_COMPLETED = 2;
    const STATUS_HOLDER_QUERY_SENT = 3;
    const STATUS_WARNING_CREATED = 5;
    const STATUS_WARNING_SENT = 6;
    const STATUS_REMINDER_SENT = 14;
    const STATUS_CANCELED = 18;
    const STATUS_DELETED = 19;

    // Status-Labels
    public const STATUS_LABELS = [
        self::STATUS_NEW => 'Neu',
        self::STATUS_IN_PROGRESS => 'In Bearbeitung',
        self::STATUS_COMPLETED => 'Abgeschlossen',
        self::STATUS_HOLDER_QUERY_SENT => 'Halterabfrage verschickt',
        self::STATUS_WARNING_CREATED => 'Abmahnung erzeugt',
        self::STATUS_WARNING_SENT => 'Abmahnung verschickt',
        self::STATUS_REMINDER_SENT => 'Mahnung verschickt',
        self::STATUS_CANCELED => 'Storniert',
        self::STATUS_DELETED => 'Gelöscht',
    ];

    // LawyerApprovalStatus-Labels
    public const LAWYER_APPROVAL_STATUS_LABELS = [
        0 => 'Nicht angefragt',
        1 => 'Freigabe angefragt',
        2 => 'Freigegeben',
    ];

    protected $fillable = [
        'plateCode1',
        'plateCode2',
        'plateCode3',
        'companyname',
        'firstname',
        'lastname',
        'email',
        'status',
        'lawyerapprovalstatus',
        'createdAt',
        'addressId',
        'status_changed_at',
        'paymentstatus',
        'adminemailsent',
        'paidkba',
        'uefileuploadedat',
        'halterdatum',
        'halterName',
        'halterStrasse',
        'halterPLZ',
        'halterOrt',
        'lat',
        'lng',
        'street',
        'zip',
        'city',
        'client_id',
        'warning_sent_at',
        'reminder_due_at',
        'reminder_sent_at',
    ];

    protected $casts = [
        'paymentstatus' => 'boolean',
        'adminemailsent' => 'boolean',
        'paidkba' => 'boolean',
        'status_changed_at' => 'datetime',
        'uefileuploadedat' => 'datetime',
        'halterdatum' => 'date',
        'lat' => 'float',
        'lng' => 'float',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
        'warning_sent_at' => 'datetime',
        'reminder_due_at' => 'datetime',
        'reminder_sent_at' => 'datetime',
    ];

    protected $appends = [
        'fullPlateCode',
        'status_label',
        'lawyerApprovalStatusLabel',
    ];

    protected $dates = [
        'createdAt',
        'updatedAt',
        'deleted_at',
        'status_changed_at',
        'date',
        'uefileuploadedat',
        'halterdatum'
    ];

    public function getFullPlateCodeAttribute(): string
    {
        return implode('-', array_filter([$this->plateCode1, $this->plateCode2, $this->plateCode3]));
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? "Unbekannt ({$this->status})";
    }

    public function getLawyerApprovalStatusLabelAttribute(): string
    {
        return self::LAWYER_APPROVAL_STATUS_LABELS[$this->lawyerapprovalstatus] ?? "Unbekannt ({$this->lawyerapprovalstatus})";
    }

    public static function getStatusOptions(): array
    {
        return self::STATUS_LABELS;
    }

    public static function getLawyerApprovalStatusOptions(): array
    {
        return self::LAWYER_APPROVAL_STATUS_LABELS;
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($report) {
            if ($report->isDirty('status')) {
                $report->status_changed_at = now();
                
                // Create status history entry
                $report->statusHistory()->create([
                    'old_status' => $report->getOriginal('status'),
                    'new_status' => $report->status,
                    'changed_by' => auth()->user()->name ?? 'System',
                ]);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'reportid');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'addressId');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(ReportStatusHistory::class);
    }

    /**
     * Get the client that owns the report.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
}
