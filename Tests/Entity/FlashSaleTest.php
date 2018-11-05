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

namespace Plugin\FlashSale\Tests\Entity;

use Eccube\Entity\ProductClass;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Service\Promotion\PromotionFactory;
use Plugin\FlashSale\Service\Condition\ConditionFactory;
use Plugin\FlashSale\Service\Rule\RuleFactory;
use Plugin\FlashSale\Tests\Entity\Rule\CartRuleTest;
use Plugin\FlashSale\Tests\Entity\Rule\ProductClassRuleTest;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Tests\Entity\Rule as RuleTest;
use Plugin\FlashSale\Tests\Entity\Promotion as PromotionTest;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class FlashSaleTest extends EccubeTestCase
{
    /**
     * @var FlashSale
     */
    protected $flashSale;

    public function setUp()
    {
        parent::setUp();
        $this->flashSale = new FlashSale();
    }

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Scenario1
     */
    public function testRawData_Scenario0($expected)
    {
        $actual = $this->flashSale->rawData(json_encode($expected['rules']));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Scenario1
     */
    public function testRawData_Scenario1($expected)
    {
        foreach ($expected['rules'] as $ruleData) {
            $rule = RuleFactory::createFromArray(['type' => $ruleData['type']]);

            $rule->setId($ruleData['id']);
            $rule->setOperator($ruleData['operator']);

            /** @var Promotion $promotion */
            $promotion = $this->container->get(PromotionFactory::class)->createFromArray(['type' => $ruleData['promotion']['type']]);
            $promotion->setId($ruleData['promotion']['id']);
            $promotion->setValue($ruleData['promotion']['value']);
            $rule->setPromotion($promotion);

            foreach ($ruleData['conditions'] as $conditionData) {
                /** @var Condition $condition */
                $condition = $this->container->get(ConditionFactory::class)->createFromArray(['type' => $conditionData['type']]);
                $condition->setId($conditionData['id']);
                $condition->setOperator($conditionData['operator']);
                $condition->setValue($conditionData['value']);
                $rule->addConditions($condition);
            }

            $this->flashSale->addRule($rule);
        }

        $actual = $this->flashSale->rawData();
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testRawData_Scenario1()
    {
        $data = [];
        $dataCase = ['rules' => []];
        foreach (CartRuleTest::dataProvider_testRawData_Scenario1() as $ruleData) {
            $dataCase['rules'] = $ruleData;
        }
        foreach (ProductClassRuleTest::dataProvider_testRawData_Scenario1() as $ruleData) {
            $dataCase['rules'] = $ruleData;
        }
        $data[] = [$dataCase];

        return $data;
    }

    /**
     * @param $rules
     * @param $object
     * @dataProvider dataProvider_testGetDiscount_S0
     */
    public function testGetDiscount_S0($rules, $object)
    {
        foreach ($rules as $rule) {
            $this->flashSale->addRule($rule);
        }
        $actual = $this->flashSale->getDiscount($object);
        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals(0, $actual->getValue());
    }

    public static function dataProvider_testGetDiscount_S0()
    {
        return [
            [[], new \stdClass()],
            [[new Rule\CartRule()], new ProductClass()],
            [[new Rule\ProductClassRule()], new Cart()],
            [[new Rule\ProductClassRule()], new Order()],
        ];
    }

//    /**
//     * @param $ruleData
//     * @param $conditionData1
//     * @param $conditionData2
//     * @param $orderSubtotal
//     * @param $promotionValue
//     * @param $promotionExpected
//     * @dataProvider dataProvider_testGetDiscount_S1
//     */
//    public function testGetDiscount_S1($ruleData, $conditionData1, $conditionData2, $orderSubtotal, $promotionValue, $promotionExpected)
//    {
//        // 2 Rule ~> Cart
//        $CartRule = new Rule\CartRule();
//        $CartRule->setId(rand());
//        $CartRule->setOperator($ruleData[0]);
//        $CartRule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//
//        $condition1 = new Condition\CartTotalCondition();
//        $condition1->setId(rand());
//        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//        $condition1->setOperator($conditionData1[0]);
//        $condition1->setValue($conditionData1[1]);
//        $CartRule->addConditions($condition1);
//
//        $condition2 = new Condition\CartTotalCondition();
//        $condition2->setId(rand());
//        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//        $condition2->setOperator($conditionData2[0]);
//        $condition2->setValue($conditionData2[1]);
//        $CartRule->addConditions($condition2);
//
//        $promotion = new Promotion\CartTotalAmountPromotion();
//        $promotion->setId(rand());
//        $promotion->setValue($promotionValue);
//        $CartRule->setPromotion($promotion);
//        $this->flashSale->addRule($CartRule);
//
//        $ProductClassRule = new Rule\ProductClassRule();
//        $this->flashSale->addRule($ProductClassRule);
//
//        $Cart = new Cart();
//        $Cart->setTotal($orderSubtotal);
//
//        $actual = $this->flashSale->getDiscount($Cart);
//        $this->assertEquals(Discount::class, get_class($actual));
//        $this->assertEquals($promotionExpected, $actual->getValue());
//    }

//    /**
//     * @param $rules
//     * @param $object
//     * @param $promotion
//     * @dataProvider dataProvider_testGetDiscount_S1
//     */
//    public function test($rules, $object, $promotion)
//    {
//        foreach ($rules as $rule) {
//            $rule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//            foreach ($rule->getConditions() as $condition) {
//                $condition->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//            }
//            $this->flashSale->addRule($rule);
//        }
//
//        $actual = $this->flashSale->getDiscount($object);
//        $this->assertEquals(Discount::class, get_class($actual));
//        $this->assertEquals($promotion->getValue(), $actual->getValue());
//    }
//
//    public static function data($testMethod = null, $orderSubtotal = 12345)
//    {
//        $ruleTypes = [Rule\CartRule::TYPE, Rule\ProductClassRule::TYPE];
//
//
//        $data = [];
//        $matchDataSet = RuleTest\CartRuleTest::dataProvider_testMatch_Scenario1($orderSubtotal);
//        foreach ($matchDataSet as $matchData) {
//            list($ruleData, $conditionData1, $conditionData2,,$expected) = $matchData;
//            if (!$expected) {
//                continue;
//            }
//
//            $CartRule = new Rule\CartRule();
//            $CartRule->setId(rand());
//            $CartRule->setOperator($ruleData[0]);
//
//            $condition1 = new Condition\CartTotalCondition();
//            $condition1->setId(rand());
//            $condition1->setOperator($conditionData1[0]);
//            $condition1->setValue($conditionData1[1]);
//            $CartRule->addConditions($condition1);
//
//            $condition2 = new Condition\CartTotalCondition();
//            $condition2->setId(rand());
//            $condition2->setOperator($conditionData2[0]);
//            $condition2->setValue($conditionData2[1]);
//            $CartRule->addConditions($condition2);
//
//            $getDiscountDataSet = PromotionTest\CartTotalAmountPromotionTest::dataProvider_testGetDiscount_Scenario1();
//            foreach ($getDiscountDataSet as $getDiscountData) {
//                list($promotionValue, $promotionExpected) = $getDiscountData;
//                $Promotion = new Promotion\CartTotalAmountPromotion();
//                $Promotion->setId(rand());
//                $Promotion->setValue($promotionValue);
//            }
//        }
//        return $data;
//    }

//    public static function dataProvider_testGetDiscount_S1($testMethod = null, $orderSubtotal = 12345)
//    {
//        $data = [];
//        $matchDataSet = RuleTest\CartRuleTest::dataProvider_testMatch_Scenario1($orderSubtotal);
//        foreach ($matchDataSet as $matchData) {
//            list($ruleData, $conditionData1, $conditionData2,,$expected) = $matchData;
//            $getDiscountDataSet = PromotionTest\CartTotalAmountPromotionTest::dataProvider_testGetDiscount_Scenario1();
//            foreach ($getDiscountDataSet as $getDiscountData) {
//                list($promotionValue, $promotionExpected) = $getDiscountData;
//                $data[] = [$ruleData, $conditionData1, $conditionData2, $orderSubtotal, $promotionValue, $expected ? $promotionExpected : 0];
//            }
//        }
//        return $data;
//    }

//    /**
//     * @param $ruleData
//     * @param $conditionData1
//     * @param $conditionData2
//     * @param $orderSubtotal
//     * @param $promotionValue
//     * @param $promotionExpected
//     * @dataProvider dataProvider_testGetDiscount_S2
//     */
//    public function testGetDiscount_S2($ruleData, $conditionData1, $conditionData2, $orderSubtotal, $promotionValue, $promotionExpected)
//    {
//        // 2 Rule ~> Order
//        $CartRule = new Rule\CartRule();
//        $CartRule->setId(rand());
//        $CartRule->setOperator($ruleData[0]);
//        $CartRule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//
//        $condition1 = new Condition\CartTotalCondition();
//        $condition1->setId(rand());
//        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//        $condition1->setOperator($conditionData1[0]);
//        $condition1->setValue($conditionData1[1]);
//        $CartRule->addConditions($condition1);
//
//        $condition2 = new Condition\CartTotalCondition();
//        $condition2->setId(rand());
//        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
//        $condition2->setOperator($conditionData2[0]);
//        $condition2->setValue($conditionData2[1]);
//        $CartRule->addConditions($condition2);
//
//        $promotion = new Promotion\CartTotalPercentPromotion();
//        $promotion->setId(rand());
//        $promotion->setValue($promotionValue);
//        $CartRule->setPromotion($promotion);
//        $this->flashSale->addRule($CartRule);
//
//        $ProductClassRule = new Rule\ProductClassRule();
//        $this->flashSale->addRule($ProductClassRule);
//
//        $Cart = new Cart();
//        $Cart->setTotal($orderSubtotal);
//
//        $actual = $this->flashSale->getDiscount($Cart);
//        $this->assertEquals(Discount::class, get_class($actual));
//        $this->assertEquals($promotionExpected, $actual->getValue());
//    }
//
//    public static function dataProvider_testGetDiscount_S2($testMethod = null, $orderSubtotal = 12345)
//    {
//        $data = [];
//        $matchDataSet = RuleTest\CartRuleTest::dataProvider_testMatch_Scenario1($orderSubtotal);
//        foreach ($matchDataSet as $matchData) {
//            list($ruleData, $conditionData1, $conditionData2,,$expected) = $matchData;
//            $getDiscountDataSet = PromotionTest\CartTotalPercentPromotionTest::dataProvider_testGetDiscount_Scenario1();
//            foreach ($getDiscountDataSet as $getDiscountData) {
//                list($promotionValue, $promotionExpected) = $getDiscountData;
//                $data[] = [$ruleData, $conditionData1, $conditionData2, $orderSubtotal, $promotionValue, $expected ? $promotionExpected : 0];
//            }
//        }
//        return $data;
//    }

//    public function testGetDiscount_S2()
//    {
//        // 2 Rule ~> Order
//    }
//
//    public function testGetDiscount_S3()
//    {
//        // 2 Rule ~> ProductClass
//    }
//
//    public static function dataProvider_testGetDiscount_S1()
//    {
//
//    }
}
