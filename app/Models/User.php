<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    public function store()
    {
        return $this->hasOne('App\Models\Store');
    }

    public function admin()
    {
        return $this->belongsTo('App\Models\Admin');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }

    public function productImages()
    {
        return $this->hasMany('App\Models\ProductImage');
    }

    public function qnas()
    {
        return $this->hasMany('App\Models\QNA');
    }

    public function qnaReplys()
    {
        return $this->hasMany('App\Models\QNAReply');
    }

    public function wishlists()
    {
        return $this->hasMany('App\Models\Wishlist');
    }

    public function carts()
    {
        return $this->hasMany('App\Models\Cart');
    }

    public function shippingAddresses()
    {
        return $this->hasMany('App\Models\ShippingAddress');
    }

    public function coupons()
    {
        return $this->hasMany('App\Models\Coupon');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}
