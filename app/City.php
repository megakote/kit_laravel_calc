<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use slowdream\kit_laravel\Kit;
use Illuminate\Support\Facades\Cache;

class City extends Model
{
    protected $table = 'cites';

    protected $fillable = ['name', 'zone'];

    /**
     * @return mixed
     */
    public function getDeliveryAttribute()
    {
        // TODO: мб лучше хранить в бд вместе с городом, а не в кеше ?
        /*if ($this->can_delivery === true && $this->can_delivery === false) {
            return $this->can_delivery;
        }*/

        // Специально поставил очень большое значение, т.к.  города будут очень редко обновляться
        $city = $this->name;
        return Cache::remember('Kit_city_' . $city, 6000, function () use ($city) {
            $data = Kit::isCity($city);
            return !!$data;
        });
    }
}
