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

use Eccube\Entity\Order;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends EccubeTestCase
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

    public function testMatch_Scenario0()
    {
        $actual = $this->cartTotalCondition->match(new \stdClass());
        $this->assertEquals(false, $actual);
    }

    /**
     * @param $conditionOperator
     * @param $conditionValue
     * @param $orderSubtotal
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($conditionOperator, $conditionValue, $orderSubtotal, $expected)
    {
        $this->cartTotalCondition->setValue($conditionValue);
        $this->cartTotalCondition->setOperator($conditionOperator);

        $Order = new Order();
        $Order->setSubtotal($orderSubtotal);

        $actual = $this->cartTotalCondition->match($Order);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario1()
    {
        return [
            ['operator_equal', 1000, 1000, true],
            ['operator_equal', 1000, 1111, false],
            ['operator_greater_than', 1000, 1111, true],
            ['operator_greater_than', 1000, 1000, false],
            ['operator_greater_than', 1000, 999, false],
            ['operator_less_than', 1000, 999, true],
            ['operator_less_than', 1000, 2222, false],
            ['operator_less_than', 1000, 1000, false],
        ];
    }
}
