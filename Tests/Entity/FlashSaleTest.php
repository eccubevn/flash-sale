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

//    public function testGetDiscount_S1()
//    {
//    }
//
//    public static function dataProvider_testGetDiscount_S1()
//    {
//
//    }
}
