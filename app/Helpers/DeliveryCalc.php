<?

namespace App\Helpers;

class DeliveryCalc
{
    private $city_one;
    private $city_two;
    private $properties;
    private $extra = 0;

    public function __construct($city_one, $city_two, array $properties)
    {
        $this->city_one = $city_one;
        $this->city_two = $city_two;
        $this->properties = $properties;

        $this->extraPrice();
    }


    /**
     *  Наценки действующие на груз
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