<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class BlogImage extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_id',
        'image_url',
        'is_primary_image',
        'is_active',
    ];

}
