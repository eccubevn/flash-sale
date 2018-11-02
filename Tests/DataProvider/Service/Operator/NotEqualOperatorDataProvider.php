<?php
namespace Plugin\FlashSale\Tests\DataProvider\Service\Operator;

class NotEqualOperatorDataProvider
{
    public static function testMatch_True1()
    {
        return [5, 10, true];
    }

    public static function testMatch_True2()
    {
        return [5000, 6666, true];
    }

    public static function testMatch_False1()
    {
        return [75, 75, false];
    }

    public static function testMatch_False2()
    {
        return [1, 1, false];
    }
}
