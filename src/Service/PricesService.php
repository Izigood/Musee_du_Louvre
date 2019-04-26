<?php

namespace App\Service;

use Symfony\Component\Yaml\Yaml;

class PricesService
{
    private $price;
    private $ageSenior;
    private $ageBaby;
    private $ageChildren;
    private $pricesSenior;
    private $pricesBaby;
    private $pricesChildren;
    private $pricesReduced;
    private $pricesNormal;
    private $coefHalfPrice;
    
    public function __CONSTRUCT()
    {
        $value          = Yaml::parseFile(__DIR__.'/DataNotification.yaml');
        $this->ageSenior      = $value['data']['ages']['senior'];
        $this->ageBaby        = $value['data']['ages']['baby'];
        $this->ageChildren    = $value['data']['ages']['children'];
        $this->pricesSenior   = $value['data']['prices']['senior'];
        $this->pricesBaby     = $value['data']['prices']['baby'];
        $this->pricesChildren = $value['data']['prices']['children'];
        $this->pricesReduced  = $value['data']['prices']['reduced'];
        $this->pricesNormal   = $value['data']['prices']['normal'];
        $this->coefHalfPrice  = $value['data']['coefficient']['halfprice'];
    }

    public function definePrice($age, $halfday, $reduced)
    {
        switch ($age)
            {
                case ($age < $this->ageBaby):
                    $this->price = $this->pricesBaby;
                    break;
                    case ($age >= $this->ageBaby && $age < $this->ageChildren):
                    $this->price = $this->pricesChildren;
                    break;
                    case ($age >= $this->ageChildren && $age < $this->ageSenior):
                    $this->price = $this->pricesNormal;
                    break;
                    case ($age >= $this->ageSenior):
                    $this->price = $this->pricesSenior;
                    break;
            }

        if($reduced == false && $halfday == true || $reduced == true && $halfday == true)
        {
            $this->price = $this->price * $this->coefHalfPrice;
        }
        elseif($reduced == true && $halfday == false && $age >= $this->ageChildren)
        {
            $this->price = $this->price - $this->pricesReduced;
        }
        else
        {
            $this->price = $this->price;
        }
        return $this->price;
    }
}