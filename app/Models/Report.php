<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use SoftDeletes;

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
    ];

    protected $attributes = [
        'order' => 0,
        'uploadStatus' => 1,
        'status' => 0,
        'sentStatus' => 0,
        'alreadyInSystem' => 0,
    ];

    protected $casts = [
        'date' => 'datetime',
        'uploadStatus' => 'integer',
        'status' => 'integer',
        'sentStatus' => 'integer',
        'alreadyInSystem' => 'integer',
        'lat' => 'double',
        'lng' => 'double',
        'order' => 'integer',
        'userId' => 'integer',
        'addressId' => 'integer',
        'createdAt' => 'datetime',
        'updatedAt' => 'datetime',
        'deletedAt' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
