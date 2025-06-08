<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'tags',
        'featured_image',
        'status',
        'published_at',
    ];
    protected $casts = [
        'status' => 'boolean',
        'published_at' => 'datetime',
    ];

    

    /**
     * Set the slug attribute automatically.
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = Str::slug($value);
    }
}
