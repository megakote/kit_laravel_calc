<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use alfamart24\laravel_tk_kit\Kit;
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
            $kit = new Kit();
            return $kit->isCity($city);
        });
    }
}
