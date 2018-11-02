<?php
namespace Plugin\FlashSale\Tests\DataProvider\Entity\Promotion;

use Eccube\Entity\Order;
use Eccube\Entity\Cart;

class CartTotalPercentPromotionDataProvider
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

    public static function testGetDiscount_Cart_True1()
    {
        $promotionData = [
            'id' => 2,
            'value' => 20,
        ];
        $Cart = new Cart();
        $Cart->setTotal(10000);
        $expected = [
            'id' => 2,
            'value' => floor($Cart->getTotal() * $promotionData['value'] / 100),
        ];
        return [
            $promotionData,
            $Cart,
            $expected,
        ];
    }

    public static function testGetDiscount_Order_True1()
    {
        $promotionData = [
            'id' => 3,
            'value' => 50,
        ];
        $Order = new Order();
        $Order->setSubtotal(84521);
        $expected = [
            'id' => 3,
            'value' => floor($Order->getSubtotal() * $promotionData['value'] / 100),
        ];
        return [
            $promotionData,
            $Order,
            $expected,
        ];
    }
}
