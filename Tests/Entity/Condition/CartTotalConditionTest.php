<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends AbstractEntityTest
{
    /**
     * @var CartTotalCondition
     */
    protected $cartTotalCondition;

    public function setUp()
    {
        parent::setUp();
        $this->cartTotalCondition = new CartTotalCondition();
        $this->cartTotalCondition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\GreaterThanOperator::TYPE,
            Operator\EqualOperator::TYPE,
            Operator\LessThanOperator::TYPE,
        ];
        $this->actual = $this->cartTotalCondition->getOperatorTypes();
        $this->verify();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($conditionData, $data, $expected) = $this->$dataSet();

        $this->cartTotalCondition->setId($conditionData['id']);
        $this->cartTotalCondition->setValue($conditionData['value']);
        $this->cartTotalCondition->setOperator($conditionData['operator']);
        $result = $this->cartTotalCondition->match($data);
        $this->assertEquals($expected, $result);
    }

    public function dataProvider_testMatch()
    {
        return [
            ['dataProvider_testMatch0'],
            ['dataProvider_testMatch1'],
            ['dataProvider_testMatch2'],
            ['dataProvider_testMatch3'],
            ['dataProvider_testMatch4'],
            ['dataProvider_testMatch5'],
            ['dataProvider_testMatch6'],
        ];
    }

    protected function dataProvider_testMatch0()
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
    protected function dataProvider_testMatch1()
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

    protected function dataProvider_testMatch2()
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

    protected function dataProvider_testMatch3()
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

    protected function dataProvider_testMatch4()
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

    protected function dataProvider_testMatch5()
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

    protected function dataProvider_testMatch6()
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

//    public function testMatch_Invalid()
//    {
//        $CartTotalCondition = new CartTotalCondition();
//        $data = $CartTotalCondition->match(new \stdClass());
//        self::assertFalse($data);
//    }
//
//    public function testMatch_InOperator_InValid_Cart()
//    {
//        $CartTotalCondition = new CartTotalCondition();
//        $CartTotalCondition->setOperator(GreaterThanOperator::TYPE);
//        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
//        $CartTotalCondition->setOperatorFactory(new OperatorFactory());
//
//        $cart = new Cart();
//        $item = new CartItem();
//        $item->setProductClass($this->ProductClass1);
//        $cart->addItem($item);
//        $cart->setTotal(10);
//        $data = $CartTotalCondition->match($cart);
//
//        self::assertFalse($data);
//    }
//
//    public function testMatch_InOperator_Valid_Order()
//    {
//        $CartTotalCondition = new CartTotalCondition();
//        $CartTotalCondition->setOperator(LessThanOperator::TYPE);
//        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
//        $CartTotalCondition->setOperatorFactory(new OperatorFactory());
//
//        $order = new Order();
//        $item = new OrderItem();
//        $item->setProductClass($this->ProductClass1);
//        $order->addItem($item);
//        $order->setTotal(100000);
//        $data = $CartTotalCondition->match($order);
//
//        self::assertTrue($data);
//    }
//
//    public function testMatch_InOperator_InValid_Order()
//    {
//        $CartTotalCondition = new CartTotalCondition();
//        $CartTotalCondition->setOperator(GreaterThanOperator::TYPE);
//        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
//        $CartTotalCondition->setOperatorFactory(new OperatorFactory());
//
//        $order = new Order();
//        $item = new OrderItem();
//        $item->setProductClass($this->ProductClass1);
//        $order->addItem($item);
//        $order->setTotal(10);
//        $data = $CartTotalCondition->match($order);
//
//        self::assertFalse($data);
//    }
}
