<?php
namespace Plugin\FlashSale\Tests\DataProvider\Entity\Promotion;

use Eccube\Entity\Order;
use Eccube\Entity\Cart;

class CartTotalAmountPromotionDataProvider
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
        return [
            [
                'id' => 2,
                'value' => 200,
            ],
            new Cart(),
            [
                'id' => 2,
                'value' => 200,
            ],
        ];
    }

    public static function testGetDiscount_Order_True1()
    {
        return [
            [
                'id' => 3,
                'value' => 300,
            ],
            new Order(),
            [
                'id' => 3,
                'value' => 300,
            ],
        ];
    }
}
