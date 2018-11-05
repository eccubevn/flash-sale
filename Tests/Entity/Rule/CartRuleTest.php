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

namespace Plugin\FlashSale\Tests\Entity\Rule;

use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Tests\Entity\Condition as ConditionTest;
use Plugin\FlashSale\Tests\Service\Operator as OperatorTest;
use Plugin\FlashSale\Tests\Entity\Promotion as PromotionTest;
use Plugin\FlashSale\Tests\Entity\RuleTest;


/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class CartRuleTest extends RuleTest
{
    /**
     * @var CartRule
     */
    protected $rule;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->rule = new CartRule();
    }

    public static function dataProvider_testRawData_Scenario1()
    {
        $data = [];
        $promotionDataSet = PromotionTest\CartTotalPercentPromotionTest::dataProvider_testRawData_Scenario1();
        foreach ($promotionDataSet as $promotionData) {
            $dataCase = [
                'id' => rand(),
                'type' => 'rule_cart',
                'operator' => array_rand(['operator_all' => 1, 'operator_or' => 1]),
                'promotion' => $promotionData[0],
                'conditions' => []
            ];
            $conditionDataSet = ConditionTest\CartTotalConditionTest::dataProvider_testRawData_Scenario1();
            foreach ($conditionDataSet as $conditionData) {
                $dataCase['conditions'][] = $conditionData[0];
            }
            $data[] = [$dataCase];
        }

        return $data;
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\OrOperator::TYPE,
            Operator\AllOperator::TYPE,
        ];
        $this->actual = $this->rule->getOperatorTypes();
        $this->verify();
    }

    public function testGetConditionTypes()
    {
        $this->expected = [
            Condition\CartTotalCondition::TYPE,
        ];
        $this->actual = $this->rule->getConditionTypes();
        $this->verify();

    }

    public function testGetPromotionTypes()
    {
        $this->expected = [
            Promotion\CartTotalPercentPromotion::TYPE,
            Promotion\CartTotalAmountPromotion::TYPE,
        ];
        $this->actual = $this->rule->getPromotionTypes();
        $this->verify();
    }

    public function testMatch_Scenario0()
    {
        $this->assertEquals(false, $this->rule->match(new \stdClass()));
    }

    /**
     * @param $ruleData
     * @param $conditionData1
     * @param $conditionData2
     * @param $orderSubTotal
     * @param $expected
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($ruleData, $conditionData1, $conditionData2, $orderSubTotal, $expected)
    {
        $this->rule->setId(rand());
        $this->rule->setOperator($ruleData[0]);
        $this->rule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));

        $condition1 = new Condition\CartTotalCondition();
        $condition1->setId(rand());
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setOperator($conditionData1[0]);
        $condition1->setValue($conditionData1[1]);
        $this->rule->addConditions($condition1);

        $condition2 = new Condition\CartTotalCondition();
        $condition2->setId(rand());
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setOperator($conditionData2[0]);
        $condition2->setValue($conditionData2[1]);

        $this->rule->addConditions($condition2);

        $Order = new Order();
        $Order->setSubtotal($orderSubTotal);

        $actual = $this->rule->match($Order);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario1($testMethod = null, $orderSubtotal = 12345)
    {
        $data = [];
        $operatorDataSet = OperatorTest\AllOperatorTest::dataProvider_testMatch_Scenario1(null, $orderSubtotal);
        foreach ($operatorDataSet as $operatorData) {
            list($conditionData1, $conditionData2,, $expected) = $operatorData;
            $data[] = [['operator_all'], $conditionData1, $conditionData2, $orderSubtotal, $expected];
        }

        $operatorDataSet = OperatorTest\OrOperatorTest::dataProvider_testMatch_Scenario1();
        foreach ($operatorDataSet as $operatorData) {
            list($conditionData1, $conditionData2,, $expected) = $operatorData;
            $data[] = [['operator_or'], $conditionData1, $conditionData2, $orderSubtotal, $expected];
        }

        return $data;
    }

    public function testGetDiscount_Scenario0()
    {
        $this->rule->setId(rand());
        $actual = $this->rule->getDiscount(new \stdClass());
        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals(0, $actual->getValue());
        $this->assertEquals($this->rule->getId(), $actual->getRuleId());
    }

    /**
     * @param $promotionValue
     * @param $orderSubTotal
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario1
     */
    public function testGetDiscount_Scenario1($orderSubTotal, $promotionValue, $expectedValue)
    {
        $this->rule->setId(rand());

        $promotion = new Promotion\CartTotalAmountPromotion();
        $promotion->setId(rand());
        $promotion->setValue($promotionValue);
        $this->rule->setPromotion($promotion);

        $Order = new Order();
        $Order->setSubtotal($orderSubTotal);

        $actual = $this->rule->getDiscount($Order);

        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals($expectedValue, $actual->getValue());
        $this->assertEquals($this->rule->getId(), $actual->getRuleId());
    }

    public function dataProvider_testGetDiscount_Scenario1($testMethod = null, $orderSubtotal = 12345)
    {
        $data = [];
        foreach (PromotionTest\CartTotalAmountPromotionTest::dataProvider_testGetDiscount_Scenario1() as $promotionData) {
            list($promotionValue, $promotionExpected) = $promotionData;
            $data[] = [$orderSubtotal, $promotionValue, $promotionExpected];
        }

        return $data;
    }

    /**
     * @param $promotionValue
     * @param $orderSubTotal
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario2
     */
    public function testGetDiscount_Scenario2($orderSubTotal, $promotionValue, $expectedValue)
    {
        $this->rule->setId(rand());

        $promotion = new Promotion\CartTotalPercentPromotion();
        $promotion->setId(rand());
        $promotion->setValue($promotionValue);
        $this->rule->setPromotion($promotion);

        $Cart = new Cart();
        $Cart->setTotal($orderSubTotal);

        $actual = $this->rule->getDiscount($Cart);

        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals($expectedValue, $actual->getValue());
        $this->assertEquals($this->rule->getId(), $actual->getRuleId());
    }

    public function dataProvider_testGetDiscount_Scenario2($testMethod = null, $orderSubtotal = 12345)
    {
        $data = [];
        foreach (PromotionTest\CartTotalPercentPromotionTest::dataProvider_testGetDiscount_Scenario1(null, $orderSubtotal) as $promotionData) {
            list($promotionValue,, $promotionExpected) = $promotionData;
            $data[] = [$orderSubtotal, $promotionValue, $promotionExpected];
        }

        return $data;
    }
}
