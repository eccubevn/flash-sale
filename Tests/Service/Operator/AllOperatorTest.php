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

namespace Plugin\FlashSale\Tests\Service\Operator;

use Eccube\Entity\Order;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Tests\Entity\Condition as ConditionTest;

class AllOperatorTest extends EccubeTestCase
{
    /**
     * @var Operator\AllOperator
     */
    protected $allOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->allOperator = new Operator\AllOperator();
    }

    public function testMatch_S0()
    {
        $this->assertEquals(false, $this->allOperator->match(null, new \stdClass()));
        $this->assertEquals(false, $this->allOperator->match([new \stdClass()], new \stdClass()));
    }

    /**
     * @param $Conditions
     * @param $Order
     * @param $expected
     * @dataProvider dataProvider_testMatch_S1_1
     * @dataProvider dataProvider_testMatch_S1_2
     */
    public function testMatch_S1($Conditions, $Order, $expected)
    {
        /** @var Condition $Condition */
        foreach ($Conditions as $Condition) {
            $Condition->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        }

        $actual = $this->allOperator->match($Conditions, $Order);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_S1_1($testMethod = null, $orderSubtotal = 12345, $productClassId = 1, $categoryId = 2)
    {
        $data = [];

        // Cart
        $tmp = ConditionTest\CartTotalConditionTest::dataProvider_testMatch_Scenario1(null, $orderSubtotal);
        $conditionDataSet = [];
        foreach ($tmp as $conditionData) {
            list($operator, $conditionValue,, $expected) = $conditionData;
            $condition = new Condition\CartTotalCondition();
            $condition->setId(rand());
            $condition->setOperator($operator);
            $condition->setValue($conditionValue);
            $conditionDataSet[] = [$condition, $expected];
        }
        $Order = new Order();
        $Order->setSubtotal($orderSubtotal);
        for ($i=0; $i<count($conditionDataSet); $i++) {
            for ($j=$i; $j<count($conditionDataSet); $j++) {
                list($conditionI, $expectedI) = $conditionDataSet[$i];
                list($conditionJ, $expectedJ) = $conditionDataSet[$j];
                $data[] = [[$conditionI, $conditionJ], $Order, $expectedI && $expectedJ];
            }
        }

        return $data;
    }

    public static function dataProvider_testMatch_S1_2($testMethod = null, $orderSubtotal = 12345, $productClassId = 1, $categoryId = 2)
    {
        $data = [];

        // Cart
        $tmp = ConditionTest\CartTotalConditionTest::dataProvider_testMatch_Scenario1(null, $orderSubtotal);
        $conditionDataSet = [];
        foreach ($tmp as $conditionData) {
            list($operator, $conditionValue,, $expected) = $conditionData;
            $condition = new Condition\CartTotalCondition();
            $condition->setId(rand());
            $condition->setOperator($operator);
            $condition->setValue($conditionValue);
            $conditionDataSet[] = [$condition, $expected];
        }
        $Order = new Order();
        $Order->setSubtotal($orderSubtotal);
        for ($i=0; $i<count($conditionDataSet); $i++) {
            for ($j=$i; $j<count($conditionDataSet); $j++) {
                list($conditionI, $expectedI) = $conditionDataSet[$i];
                list($conditionJ, $expectedJ) = $conditionDataSet[$j];
                $data[] = [[$conditionI, $conditionJ], $Order, $expectedI && $expectedJ];
            }
        }

        return $data;
    }
}
