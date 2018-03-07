<?

namespace App\Helpers;

use App\City;
use App\Product;
use App\DeliveryPrice;
use alfamart24\laravel_tk_kit\Kit;

class DeliveryCalc
{

    /**
     * DeliveryCalc constructor.
     *
     * @param City $city_one
     * @param City $city_two
     * @param Product $product
     *
     * @return DeliveryPrice
     */
    public function __construct(City $city_one, City $city_two, Product $product)
    {
        $cites = $this->sortCites($city_one, $city_two);

        $city_one = $cites[0];
        $city_two = $cites[1];

        $price = DeliveryPrice::where(
          ['volume', round($product->volume, 2, PHP_ROUND_HALF_UP)],
          ['weight', $this->my_round($product->weight, 50)],
          ['city_one_id', $city_one->id],
          ['city_two_id', $city_two->id]
        )->get();

        // Если не нашел данных в базе, то делаем запрос к сервису
        if (!$price) {
            $price = $this->process($city_one, $city_two, $product);
        }

        // Если по какой-то причине пришел false, то его и отдаем
        if (!$price) {
            return false;
        }

        // Если цена товара больше 50к, то за каждые 50к накидываем еще 50р цены доставки за хз что
        $price->addition_price = ($product->price > 50000) ? round($product->price / 50000, 0) * 50 : 0;

        return $price;
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
    private function process(City $city_one, City $city_two, Product $product)
    {
        $city_one_data = $city_one->delivery;
        $city_two_data = $city_two->delivery;
        if ((!$city_one_data || !$city_two_data) || ($city_one->name == $city_two->name)) {
            return false;
        }

        $productOptions = [
          'WEIGHT' => $product->weight,
          'VOLUME' => $product->volume,
          //'PRICE' => $product->price
          'PRICE' => 1000
        ];

        $kit = new Kit();
        $data = $kit->priceOrderSlim($productOptions, $city_one_data, $city_two_data);
        /*
         * За каждые 50к стоимость груза добавляется 50р (тут считаем по минимуму, а накидываем уже в контроллере)
         * Вес груза округляем до 50кг в большую сторону
         * Объем округлить не получится
         *
         * Специально не меняю updateOrCreate на create т.к. возможно будем обновлять устаревшие данные.
        */
        return DeliveryPrice::updateOrCreate(
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
        return (int)((int)($a / $n) + ceil($a % $n / $n)) * $n;
    }

    /**
     *  Наценки действующие на груз
     *  Сейчас это не нужно тут, просто удалять жалко
     */
    private function extraPrice()
    {
        // Превышение гарабита по массе
        if ($this->properties['weight'] > 1500) {
            $this->extra += 0.5;
        } elseif ($this->properties['weight'] > 1000) {
            $this->extra += 0.25;
        } elseif ($this->properties['weight'] > 500) {
            $this->extra += 0.1;
        }

        // Превышение гарабита по Размеру одной из сторон
        if ($this->properties['length'] > 900 || $this->properties['width'] > 900 || $this->properties['height'] > 900) {
            $this->extra += 0.5;
        } elseif ($this->properties['length'] > 600 || $this->properties['width'] > 600 || $this->properties['height'] > 600) {
            $this->extra += 0.3;
        } elseif ($this->properties['length'] > 400 || $this->properties['width'] > 400 || $this->properties['height'] > 400) {
            $this->extra += 0.2;
        }
    }
}