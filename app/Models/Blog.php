<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'is_featured',
        'is_active',
    ];

    public function blogImage(){
        return $this->hasMany('App\Models\BlogImage','blog_id');
    }

}
