<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'image'
    ];

    protected $appends = ['image_path'];
    protected $hidden = ['image'];

    public function getImagePathAttribute()
    {
        return config('siteConfig.image_path') . $this->attributes['image'];
    }
}
