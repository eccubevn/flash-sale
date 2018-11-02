<?php
namespace Plugin\FlashSale\Tests\Service\Operator;

trait InOperatorDataProviderTrait
{
    public static function dataProvider_testMatch_True1()
    {
        return [[5,1,2,999], 1, true];
    }

    public static function dataProvider_testMatch_True2()
    {
        return ['10,2,5,8,9', 2, true];
    }

    public static function dataProvider_testMatch_False1()
    {
        return [[56,10,55,13], 3, false];
    }

    public static function dataProvider_testMatch_False2()
    {
        return ['78,9,75,12', 4, false];
    }
}
