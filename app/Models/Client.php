<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $connection = 'reports';

    protected $fillable = [
        'firstname',
        'lastname',
    ];

    /**
     * Get the reports for the client.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }
}
