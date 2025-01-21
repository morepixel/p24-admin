<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'addresses';

    protected $fillable = [
        'name',
        'street',
        'zip',
        'city',
        'country',
        'lat',
        'lng'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'addressid');
    }
}
