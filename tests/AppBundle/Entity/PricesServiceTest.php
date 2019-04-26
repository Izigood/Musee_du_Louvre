<?php

namespace Tests\AppBundle\Entity;

use App\Service\PricesService;
use PHPUnit\Framework\TestCase;

class PricesServiceTest extends TestCase
{
    public function testPrice1()
    {
        $price = new PricesService;
        $result = $price->definePrice('60', true, false);

        $this->assertSame(6.0, $result);
    }

    public function testPrice2()
    {
        $price = new PricesService;
        $result = $price->definePrice('18', false, true);

        $this->assertSame(6.0, $result);
    }

    public function testPrice3()
    {
        $price = new PricesService;
        $result = $price->definePrice('3', true, true);

        $this->assertSame(0.0, $result);
    }

    public function testPrice4()
    {
        $price = new PricesService;
        $result = $price->definePrice('9', true, true);

        $this->assertSame(4.0, $result);
    }
}