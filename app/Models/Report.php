<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'reports';

    public $timestamps = true;

    protected $fillable = [
        'companyName',
        'firstname',
        'lastname',
        'email',
        'plateCode1',
        'plateCode2',
        'plateCode3',
        'date',
        'uploadStatus',
        'status',
        'sentStatus',
        'alreadyInSystem',
        'city',
        'zip',
        'street',
        'country',
        'lat',
        'lng',
        'order',
        'userId',
        'addressId',
        'lawyerDetails',
        'halterDatum',
        'halterName',
        'zahlungsziel',
        'kennnummer',
        'halterPLZ',
        'halterOrt',
        'halterStrasse',
        'kbaFile',
        'paymentStatus',
        'adminEmailSent',
        'fahrerName',
        'fahrerOrt',
        'fahrerPLZ',
        'fahrerStrasse',
        'fahrerGeschlecht',
        'halterGeschlecht',
        'mandantGeschlecht',
        'notes',
        'ueFIle',
        'ueFileUploadedAt',
        'reportResponse',
        'paidKBA',
        'lawyerApprovalStatus',
    ];

    protected $casts = [
        'date' => 'datetime',
        'ueFileUploadedAt' => 'datetime',
        'paymentStatus' => 'boolean',
        'adminEmailSent' => 'boolean',
        'paidKBA' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'status' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
