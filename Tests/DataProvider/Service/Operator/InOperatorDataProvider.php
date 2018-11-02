<?php
namespace Plugin\FlashSale\Tests\DataProvider\Service\Operator;

class InOperatorDataProvider
{
    public static function testMatch_True1()
    {
        return [[5,1,2,999], 1, true];
    }

    public static function testMatch_True2()
    {
        return ['10,2,5,8,9', [2,5], true];
    }

    public static function testMatch_False1()
    {
        return [[56,10,55,13], 3, false];
    }

    public static function testMatch_False2()
    {
        return ['78,9,75,12', 4, false];
    }
}
