<?php
namespace Plugin\FlashSale\Tests\DataProvider\Entity\Condition;

use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\InOperatorDataProvider;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\NotInOperatorDataProvider;

class ProductClassIdConditionDataProvider
{
    protected static function testMatchByOperator(array $operator)
    {
        $conditionData  = [
            'id' => 1,
            'operator' => $operator['type'],
            'value' => is_array($operator['condition']) ? implode(',', $operator['condition']) : $operator['condition']
        ];

        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => is_array($operator['data']) ? current($operator['data']): $operator['data']]);
        $expected = $operator['expected'];
        return [
            $conditionData,
            $ProductClass,
            $expected
        ];
    }

    public static function testMatch_False1()
    {
        $conditionData  = [
            'id' => 1,
            'operator' => Operator\InOperator::TYPE,
            'value' => 100
        ];
        $ProductClass = new \stdClass();
        $expected = false;
        return [
            $conditionData,
            $ProductClass,
            $expected
        ];
    }

    public static function testMatch_InOperator_True1()
    {
        $operator['type'] = Operator\InOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = InOperatorDataProvider::testMatch_True1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_InOperator_False1()
    {
        $operator['type'] = Operator\InOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = InOperatorDataProvider::testMatch_False1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_InOperator_True2()
    {
        $operator['type'] = Operator\InOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = InOperatorDataProvider::testMatch_True2();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_InOperator_False2()
    {
        $operator['type'] = Operator\InOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = InOperatorDataProvider::testMatch_False2();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_NotInOperator_True1()
    {
        $operator['type'] = Operator\NotInOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = NotInOperatorDataProvider::testMatch_True1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_NotInOperator_False1()
    {
        $operator['type'] = Operator\NotInOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = NotInOperatorDataProvider::testMatch_False1();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_NotInOperator_True2()
    {
        $operator['type'] = Operator\NotInOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = NotInOperatorDataProvider::testMatch_True2();
        return static::testMatchByOperator($operator);
    }

    public static function testMatch_NotInOperator_False2()
    {
        $operator['type'] = Operator\NotInOperator::TYPE;
        list($operator['condition'], $operator['data'], $operator['expected']) = NotInOperatorDataProvider::testMatch_False2();
        return static::testMatchByOperator($operator);
    }
}