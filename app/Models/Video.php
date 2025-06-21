<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'title',
        'description',
        'filename',
        'original_filename',
        'mime_type',
        'size',
        'path',
        'folder_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
