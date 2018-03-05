<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryPrice extends Model
{
    protected $table = 'delivery_prices';

    protected $fillable = ['city_one_id', 'city_two_id', 'volume', 'weight', 'info'];

    public function cityOne ()
    {
        return $this->hasOne('App\City', 'city_one_id');
    }

    public function cityTwo ()
    {
        return $this->hasOne('App\City', 'city_two_id');
    }
}
