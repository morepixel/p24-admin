<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $connection = 'reports';

    public $timestamps = false;

    protected $fillable = [
        'filename',
        'path',
        'type',
        'reportid'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'reportid');
    }
}
