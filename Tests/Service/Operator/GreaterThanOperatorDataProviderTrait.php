<?php
namespace Plugin\FlashSale\Tests\Service\Operator;

trait GreaterThanOperatorDataProviderTrait
{
    public static function dataProvider_testMatch_True1()
    {
        return [5, 10, true];
    }

    public static function dataProvider_testMatch_True2()
    {
        return [5000, 5100, true];
    }

    public static function dataProvider_testMatch_False1()
    {
        return [500, 56, false];
    }

    public static function dataProvider_testMatch_False2()
    {
        return [5000, 1000, false];
    }
}
