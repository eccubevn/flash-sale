<?php
namespace Plugin\FlashSale\Tests\DataProvider\Entity\Promotion;

use Eccube\Entity\Cart;
use Eccube\Entity\ProductClass;

class ProductClassPricePercentPromotionDataProvider
{
    public static function testGetDiscount_False1()
    {
        return [
            [
                'id' => 1,
                'value' => 100,
            ],
            new \stdClass(),
            [
                'id' => 1,
                'value' => 0,
            ],
        ];
    }

    public static function testGetDiscount_False2()
    {
        return [
            [
                'id' => 2,
                'value' => 200,
            ],
            new Cart(),
            [
                'id' => 2,
                'value' => 0,
            ],
        ];
    }

    public static function testGetDiscount_True1()
    {
        $promotionData = [
            'id' => 3,
            'value' => 10,
        ];
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02IncTax(12121122);
        $expected = [
            'id' => 3,
            'value' => floor($ProductClass->getPrice02IncTax() * $promotionData['value'] / 100),
        ];
        return [
            $promotionData,
            $ProductClass,
            $expected,
        ];
    }
}
