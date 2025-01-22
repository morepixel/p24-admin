<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    protected $connection = 'addresses';
    
    protected $table = 'addresses';

    protected $fillable = [
        'poaFile',
        'poaFileUploadedAt',
    ];

    protected $casts = [
        'poaFileUploadedAt' => 'datetime',
    ];

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'addressId');
    }
}
