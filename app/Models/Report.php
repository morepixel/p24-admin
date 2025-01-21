<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'reports';

    public $timestamps = false;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

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
    ];

    protected $casts = [
        'createdAt' => 'datetime',
    ];

    protected $appends = [
        'fullPlateCode',
        'status_label',
    ];

    protected $dates = [
        'createdAt',
        'updatedAt',
        'deleted_at',
    ];

    public function getFullPlateCodeAttribute()
    {
        return implode('-', array_filter([$this->plateCode1, $this->plateCode2, $this->plateCode3]));
    }

    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            0 => 'Neu',
            1 => 'In Bearbeitung',
            2 => 'Abgeschlossen',
            3 => 'Halteranfrage versendet',
            4 => 'Halteranfrage empfangen',
            5 => 'Abmahnung erstellt',
            6 => 'Abmahnung versendet',
            18 => 'Gelöscht',
            19 => 'Storniert',
            default => 'Unbekannt',
        };
    }

    public static function getStatusOptions(): array
    {
        return [
            0 => 'Neue Reports',
            1 => 'Neuer Vorgang ohne Vollmacht',
            2 => 'Neuer Vorgang mit Vollmacht',
            3 => 'Halteranfrage versendet',
            4 => 'Halteranfrage empfangen',
            5 => 'Abmahnung erstellt',
            6 => 'Abmahnung versendet',
            18 => 'Gelöscht',
            19 => 'Storniert',
        ];
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'reportid');
    }
}
