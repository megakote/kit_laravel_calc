<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model
{
    protected $table = 'delivery_prices';

    protected $fillable = ['product_id', 'city_one_id', 'city_two_id', 'volume', 'price', 'info'];

    private $volume_range = ['0-1','1-2','2-3','3-4'];
    private $price_range = [''];

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
