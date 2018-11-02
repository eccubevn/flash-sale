<?php
namespace Plugin\FlashSale\Tests\Entity\Condition;

use Eccube\Entity\Cart;
use Eccube\Entity\CartItem;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;
use Plugin\FlashSale\Service\Operator as Operator;

trait CartTotalConditionDataProviderTrait
{
    public static function dataProvider_testMatch0()
    {
        $conditionData  = [
            'id' => 1,
            'operator' => Operator\InOperator::TYPE,
            'value' => 100
        ];
        $data = new \stdClass();
        $expected = false;
        return [
            $conditionData,
            $data,
            $expected
        ];
    }
    public static function dataProvider_testMatch1()
    {
        $conditionData  = [
            'id' => 2,
            'operator' => Operator\EqualOperator::TYPE,
            'value' => 100
        ];
        $Order = new Order();
        $Order->setSubtotal(5000);
        $expected = ($conditionData['value'] == $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function dataProvider_testMatch2()
    {
        $conditionData  = [
            'id' => 3,
            'operator' => Operator\EqualOperator::TYPE,
            'value' => 100
        ];
        $Order = new Order();
        $Order->setSubtotal($conditionData['value']);
        $expected = ($conditionData['value'] == $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function dataProvider_testMatch3()
    {
        $conditionData  = [
            'id' => 4,
            'operator' => Operator\GreaterThanOperator::TYPE,
            'value' => 200
        ];
        $Order = new Order();
        $Order->setSubtotal($conditionData['value']*5);
        $expected = ($conditionData['value'] < $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function dataProvider_testMatch4()
    {
        $conditionData  = [
            'id' => 5,
            'operator' => Operator\GreaterThanOperator::TYPE,
            'value' => 500
        ];
        $Order = new Order();
        $Order->setSubtotal($conditionData['value']);
        $expected = ($conditionData['value'] < $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function dataProvider_testMatch5()
    {
        $conditionData  = [
            'id' => 6,
            'operator' => Operator\LessThanOperator::TYPE,
            'value' => 700
        ];
        $Order = new Order();
        $Order->setSubtotal($conditionData['value']);
        $expected = ($conditionData['value'] > $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function dataProvider_testMatch6()
    {
        $conditionData  = [
            'id' => 7,
            'operator' => Operator\LessThanOperator::TYPE,
            'value' => 560
        ];
        $Order = new Order();
        $Order->setSubtotal($conditionData['value'] - 1);
        $expected = ($conditionData['value'] > $Order->getSubtotal());
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }
}
