<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model
{
    protected $table = 'delivery_prices';

    protected $fillable = ['product_id', 'city_one_id', 'city_two_id'];

    public function product ()
    {
        return $this->hasOne('App\Product');
    }

    public function cityOne ()
    {
        return $this->hasOne('App\City', 'city_one_id');
    }

    public function cityTwo ()
    {
        return $this->hasOne('App\City', 'city_two_id');
    }
}
