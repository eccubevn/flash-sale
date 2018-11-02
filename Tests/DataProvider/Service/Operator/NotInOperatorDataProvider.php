<?php
namespace Plugin\FlashSale\Tests\DataProvider\Service\Operator;

class NotInOperatorDataProvider
{
    public static function testMatch_True1()
    {
        return [[5,1,2,999], 4, true];
    }

    public static function testMatch_True2()
    {
        return ['10,2,5,8,9', 11, true];
    }

    public static function testMatch_False1()
    {
        return [[56,10,55,13], 10, false];
    }

    public static function testMatch_False2()
    {
        return ['78,9,75,12', 9, false];
    }
}
