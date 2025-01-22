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

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'reportid');
    }
}
