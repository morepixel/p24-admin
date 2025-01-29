<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportStatusHistory extends Model
{
    protected $table = 'report_status_history';

    protected $fillable = [
        'report_id',
        'old_status',
        'new_status',
        'changed_by',
        'comment',
    ];

    protected $casts = [
        'old_status' => 'integer',
        'new_status' => 'integer',
    ];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function getOldStatusLabelAttribute(): string
    {
        return Report::STATUS_LABELS[$this->old_status] ?? "Unbekannt ({$this->old_status})";
    }

    public function getNewStatusLabelAttribute(): string
    {
        return Report::STATUS_LABELS[$this->new_status] ?? "Unbekannt ({$this->new_status})";
    }
}
