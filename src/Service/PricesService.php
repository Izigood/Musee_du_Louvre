<?php

namespace App\Service;

class PricesService
{
    private $price;
    private $age;
    
    // public function __CONSTRUCT()
    // {
        
    // }

    public function definePrice($age, $halfday, $reduced)
    {
        // if($reduced == false && $halfday == true)
        // {
        //     $this->price = $this->price * 0.5;
        // }
        
        // elseif($reduced == true && $halfday == false)
        // {
        //     $this->price = $this->price - 10;
        // }

        // switch ($age)
        //     {
        //         case ($age < 4):
        //             $price = 0;
        //             return $this->price;
        //             break;
        //             case ($age >= 4 && $age <= 12):
        //             $this->price = 8;
        //             return $this->price;
        //             break;
        //             case ($age > 12):
        //             $this->price = 16;
        //             return $this->price;
        //             break;
        //             case ($age >= 60):
        //             $this->price = 12;
        //             return $this->price;
        //             break;
        //     }
        $this->price = 16;

        if($reduced == true && $halfday == false)
        {
            $price = $this->price * 0.5;
            return $price;
        }
        elseif($reduced == false && $halfday == true)
        {
            $price = $this->price - 10;
            return $price;
        }
        
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }
}