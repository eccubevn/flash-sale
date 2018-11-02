<?php
namespace Plugin\FlashSale\Tests\DataProvider\Service\Operator;

class EqualOperatorDataProvider
{
    public static function testMatch_True1()
    {
        return [5, 5, true];
    }

    public static function testMatch_True2()
    {
        return [5000, 5000, true];
    }

    public static function testMatch_False1()
    {
        return [500, 5000, false];
    }

    public static function testMatch_False2()
    {
        return [5000, 1000, false];
    }
}
