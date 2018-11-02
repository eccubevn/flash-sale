<?php
namespace Plugin\FlashSale\Tests\Service\Operator;

trait NotEqualOperatorDataProviderTrait
{
    public static function dataProvider_testMatch_True1()
    {
        return [5, 10, true];
    }

    public static function dataProvider_testMatch_True2()
    {
        return [5000, 6666, true];
    }

    public static function dataProvider_testMatch_False1()
    {
        return [75, 75, false];
    }

    public static function dataProvider_testMatch_False2()
    {
        return [1, 1, false];
    }
}
