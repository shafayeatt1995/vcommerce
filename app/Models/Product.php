<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function store()
    {
        return $this->belongsTo('App\Models\Store');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory');
    }

    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function featureDescriptions()
    {
        return $this->hasMany('App\Models\FeatureDescription');
    }

    public function colors()
    {
        return $this->hasMany('App\Models\Color');
    }

    public function variants()
    {
        return $this->hasMany('App\Models\Variant');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }

    public function specifications()
    {
        return $this->hasMany('App\Models\Specification');
    }

    public function qnas()
    {
        return $this->hasMany('App\Models\QNA');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function scopeActive($query)
    {
        return $query->where('block',false);
    }

    public function scopeStatus($query)
    {
        return $query->where('status',true);
    }
}
