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
        $mainCity = City::firstOrNew(['name' => 'Москва']);

        foreach ($products as $product) {
            foreach ($cites as $city) {
                $this->process($mainCity, $city, $product);
            }
        }
    }

    /**
     * Вычисляет приблизительную(?) стоимость доставки груза $product от $city_one до $city_two
     *
     * @param City $city_one
     * @param City $city_two
     * @param Product $product
     *
     * @return bool
     */
    public function process(City $city_one, City $city_two, Product $product)
    {

        $cites = $this->sortCites($city_one, $city_two);

        $city_one = $cites[0];
        $city_two = $cites[1];

        $city_one_data = $city_one->delivery;
        $city_two_data = $city_two->delivery;
        if ((!$city_one_data || !$city_two_data) || ($city_one->name == $city_two->name) ) {
            return false;
        }

        $productOptions = [
          'WEIGHT' => $product->weight,
          'VOLUME' => $product->volume,
          'PRICE' => $product->price
        ];

        $kit = new Kit();
        $data = $kit->priceOrderSlim($productOptions, $city_one_data, $city_two_data);
        /*
         * За каждые 50к стоимость груза добавляется 50р (тут считаем по минимуму, а накидываем уже в контроллере)
         * Вес груза округляем до 50кг в большую сторону
         * Объем округлить не получится
         *
        */
        DeliveryPrice::updateOrCreate(
          [
            'volume' => round($product->volume, 2, PHP_ROUND_HALF_UP),
            'weight' => $this->my_round($product->weight, 50),
            'city_one_id' => $city_one->id,
            'city_two_id' => $city_two->id,
          ],
          [
            'info' => json_encode($data['data'])
          ]
        );

    }

    /**
     * Защита от дублей когда записи отличаются только разным порядком городов
     *
     * @param City $city_one
     * @param City $city_two
     *
     * @return array
     */
    private function sortCites(City $city_one, City $city_two)
    {
        $array = [$city_one->name, $city_two->name];
        $array = sort($array);

        if ($array[0] == $city_one->name) {
            return [$city_one, $city_two];
        } else {
            return [$city_two, $city_one];
        }
    }

    /**
     * Округляет $a до $n в большую сторону
     *
     * @param $a
     * @param $n
     *
     * @return int
     */
    private function my_round($a, $n)
    {
        return (int) ((int)($a / $n) + ceil($a % $n / $n)) * $n;
    }

}
