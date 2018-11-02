<?php
namespace Plugin\FlashSale\Tests\DataProvider\Service\Operator;

class LessThanOperatorDataProvider
{
    public static function testMatch_True1()
    {
        return [10, 5, true];
    }

    public static function testMatch_True2()
    {
        return [5200, 100, true];
    }

    public static function testMatch_False1()
    {
        return [5, 56, false];
    }

    public static function testMatch_False2()
    {
        return [123, 456, false];
    }
}
