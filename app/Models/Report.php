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
        'userId',
        'addressId',
        'status',
        'plateCode1',
        'plateCode2',
        'plateCode3',
        'date',
        'uploadStatus',
        'sentStatus',
        'alreadyInSystem',
        'city',
        'street',
        'zip',
        'country',
        'lat',
        'lng',
        'order',
        'lawyerDetails',
        'halterDatum',
        'halterName',
        'halterStrasse',
        'halterPLZ',
        'halterOrt',
        'fahrerName',
        'fahrerStrasse',
        'fahrerPLZ',
        'fahrerOrt',
        'fahrerGeschlecht',
        'halterGeschlecht',
        'mandantGeschlecht',
        'notes',
        'ueFile',
        'ueFileUploadedAt',
        'reportResponse',
        'paymentStatus',
        'adminEmailSent',
        'paidKBA',
        'lawyerApprovalStatus',
        'description',
        'companyname',
        'firstname',
        'lastname',
        'email',
        'kennzeichen',
        'stelle',
        'plz',
        'kennnummer',
        'zahlungsziel',
        'kbafile',
        'fahrergeschlecht',
        'haltergeschlecht',
        'mandantgeschlecht'
    ];

    protected $dates = [
        'createdAt',
        'updatedAt',
        'deleted_at',
        'date',
        'halterDatum',
        'ueFileUploadedAt'
    ];

    public function getHasImagesAttribute(): bool
    {
        return $this->images()->count() > 0;
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'reportid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }

    public function address()
    {
        return $this->belongsTo(Address::class, 'addressId');
    }

    public function getFullPlateCodeAttribute()
    {
        return implode('-', array_filter([
            $this->plateCode1,
            $this->plateCode2,
            $this->plateCode3
        ]));
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            '0' => 'Neu',
            '1' => 'In Bearbeitung',
            '2' => 'Abgeschlossen',
            '3' => 'Halterabfrage abgeschickt',
            '4' => 'Halterabfrage zurück',
            '5' => 'Abmahnung erzeugt',
            '6' => 'Abmahnung verschickt',
            '18' => 'Gelöscht',
            '19' => 'Storniert',
            default => "Status {$this->status}",
        };
    }

    public static function getStatusOptions(): array
    {
        return [
            0 => 'Neue Reports',
            1 => 'Neuer Vorgang ohne Vollmacht',
            2 => 'Neuer Vorgang mit Vollmacht',
            3 => 'Halterabfrage abgeschickt',
            4 => 'Halterabfrage zurück',
            5 => 'Abmahnung erzeugt',
            6 => 'Abmahnung verschickt',
            18 => 'Gelöscht',
            19 => 'Storniert',
        ];
    }
}
