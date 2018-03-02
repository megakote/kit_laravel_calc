<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Product;
use App\City;
use App\DeliveryPrice;
use alfamart24\laravel_tk_kit\Kit;


class GetProductsDeliveryPrice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $products;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Collection $products)
    {
        $this->products = $products;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = $this->products;
        $cites = City::all();
        $mainCity = City::new('name', 'Москва');

        foreach ($products as $product) {
            foreach ($cites as $city) {
                $this->process($mainCity, $city, $product);
            }
        }
    }

    public function process (City $city_one, City $city_two, Product $product) {

        $cites = $this->sortCites($city_one, $city_two);
        $city_one = $cites[0];
        $city_two = $cites[1];

        // На данный момент этот функционал дублируется в библиотеке
        if (!$city_one->delivery || !$city_two->delivery)
            return false;

        $productOptions = [
          'WEIGHT' => $product->weight,
          'LENGTH' => $product->length,
          'WIDTH' => $product->width,
          'HEIGHT' => $product->height,
          'PRICE' => $product->price
        ];

        //TODO: информация по городам лучше тоже хранить в нашей базе, будет на 2 меньше запроса.

        $data = Kit::priceOrder($productOptions, $city_one->name, $city_two->name);

        // На данный момент этот функционал дублируется в модели
        if(!$data)
            return false;

        DeliveryPrice::updateOrCreate(
            [
              'volume' => $product->volume, // TODO: тут выставить округление или диапозоны брать.
              'price' => $product->price, // TODO: тут выставить округление или диапозоны брать.
              'city_one_id' => $city_one->id,
              'city_two_id' => $city_two->id,
            ],
            [
              'info' => json_encode($data)
            ]
        );

    }

    // TODO: защита от дублей когда записи отличаются только разным порядком городов
    private function sortCites(City $city_one, City $city_two) {
        return [$city_one, $city_two];
    }
}
