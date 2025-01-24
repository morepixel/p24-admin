<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasFactory;

    protected $connection = 'reports';

    public $timestamps = false;

    protected $fillable = [
        'filename',
        'path',
        'type',
        'reportid',
        'url'
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'reportid');
    }
}
