<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = ['name', 'weight', 'length', 'width', 'height', 'price'];

    public function getVolumeAttribute()
    {
        // Переводим в метры кубические.
        return ($this->width * $this->height * $this->length) /  1000000 ;
    }
}
