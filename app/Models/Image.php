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

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if (!empty($this->attributes['url'])) {
            return $this->attributes['url'];
        }

        if (!empty($this->attributes['path'])) {
            return Storage::disk('reports')->url($this->attributes['path']);
        }

        return null;
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'reportid');
    }
}
