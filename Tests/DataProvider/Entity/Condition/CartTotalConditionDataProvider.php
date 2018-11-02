<?php
namespace Plugin\FlashSale\Tests\DataProvider\Entity\Condition;

use Plugin\FlashSale\Service\Operator;
use Eccube\Entity\Order;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\EqualOperatorDataProvider;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\GreaterThanOperatorDataProvider;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\LessThanOperatorDataProvider;

class CartTotalConditionDataProvider
{
    protected static function testMatchByOperator(array $operator)
    {
        $conditionData  = [
            'id' => 1,
            'operator' => $operator['type'],
            'value' => $operator['condition']
        ];
        $Order = new Order();
        $Order->setSubtotal($operator['data']);
        $expected = $operator['expected'];
        return [
            $conditionData,
            $Order,
            $expected
        ];
    }

    public static function testMatch_EqualOperator_True1()
    {
        $operator['type'] = Operator\EqualOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = EqualOperatorDataProvider::testMatch_True1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_GreaterThanOperator_True1()
    {
        $operator['type'] = Operator\GreaterThanOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = GreaterThanOperatorDataProvider::testMatch_True1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_LessThanOperator_True1()
    {
        $operator['type'] = Operator\LessThanOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = LessThanOperatorDataProvider::testMatch_True1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_False1()
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

    public static function testMatch_EqualOperator_False1()
    {
        $operator['type'] = Operator\EqualOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = EqualOperatorDataProvider::testMatch_False1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_GreaterThanOperator_False1()
    {
        $operator['type'] = Operator\GreaterThanOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = GreaterThanOperatorDataProvider::testMatch_False1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_LessThanOperator_False1()
    {
        $operator['type'] = Operator\LessThanOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = LessThanOperatorDataProvider::testMatch_False1();
        return static::testMatchByOperator($operator);
    }
}
