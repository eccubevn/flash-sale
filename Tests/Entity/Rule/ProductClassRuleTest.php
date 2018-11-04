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

use Eccube\Entity\ProductCategory;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Tests\Entity\RuleTest;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Tests\Service\Operator as OperatorTest;
use Plugin\FlashSale\Tests\Entity\Promotion as PromotionTest;
use Plugin\FlashSale\Tests\Entity\Condition as ConditionTest;

/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class ProductClassRuleTest extends RuleTest
{
    /**
     * @var ProductClassRule
     */
    protected $rule;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->rule = new ProductClassRule();
    }

    public static function dataProvider_testRawData_Scenario1()
    {
        $data = [];
        $promotionDataSet = PromotionTest\ProductClassPriceAmountPromotionTest::dataProvider_testRawData_Scenario1();
        foreach ($promotionDataSet as $promotionData) {
            $dataCase = [
                'id' => rand(),
                'type' => 'rule_product_class',
                'operator' => array_rand(['operator_all' => 1, 'operator_or' => 1]),
                'promotion' => $promotionData[0],
                'conditions' => []
            ];
            $conditionDataSet = ConditionTest\ProductClassIdConditionTest::dataProvider_testRawData_Scenario1();
            foreach ($conditionDataSet as $conditionData) {
                $dataCase['conditions'][] = $conditionData[0];
            }
            $data[] = [$dataCase];
        }
        $promotionDataSet = PromotionTest\ProductClassPricePercentPromotionTest::dataProvider_testRawData_Scenario1();
        foreach ($promotionDataSet as $promotionData) {
            $dataCase = [
                'id' => rand(),
                'type' => 'rule_product_class',
                'operator' => array_rand(['operator_all' => 1, 'operator_or' => 1]),
                'promotion' => $promotionData[0],
                'conditions' => []
            ];
            $conditionDataSet = ConditionTest\ProductCategoryIdConditionTest::dataProvider_testRawData_Scenario1();
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
            Condition\ProductClassIdCondition::TYPE,
            Condition\ProductCategoryIdCondition::TYPE,
        ];
        $this->actual = $this->rule->getConditionTypes();
        $this->verify();
    }

    public function testGetPromotionTypes()
    {
        $this->expected = [
            Promotion\ProductClassPricePercentPromotion::TYPE,
            Promotion\ProductClassPriceAmountPromotion::TYPE,
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
     * @param $productClassId
     * @param $categoryId
     * @param $expected
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($ruleData, $conditionData1, $conditionData2, $productClassId, $categoryId, $expected)
    {
        $this->rule->setId(rand());
        $this->rule->setOperator($ruleData[0]);
        $this->rule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));

        $condition1 = new Condition\ProductClassIdCondition();
        $condition1->setId(rand());
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setOperator($conditionData1[0]);
        $condition1->setValue($conditionData1[1]);
        $this->rule->addConditions($condition1);

        $condition2 = new Condition\ProductCategoryIdCondition();
        $condition2->setId(rand());
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setOperator($conditionData2[0]);
        $condition2->setValue($conditionData2[1]);

        $this->rule->addConditions($condition2);

        $ProductCategory = new ProductCategory();
        $ProductCategory->setCategoryId($categoryId);
        $Product = new Product();
        $Product->addProductCategory($ProductCategory);
        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => $productClassId]);
        $ProductClass->setProduct($Product);

        $actual = $this->rule->match($ProductClass);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch_Scenario1($testMethod = null, $productClassId = 1, $categoryId = 2)
    {
        $data = [];
        $operatorDataSet = OperatorTest\AllOperatorTest::dataProvider_testMatch_Scenario2(null, $productClassId, $categoryId);
        foreach ($operatorDataSet as $operatorData) {
            list($conditionData1, $conditionData2,,, $expected) = $operatorData;
            $data[] = [['operator_all'], $conditionData1, $conditionData2, $productClassId, $categoryId, $expected];
        }

        $operatorDataSet = OperatorTest\OrOperatorTest::dataProvider_testMatch_Scenario2(null, $productClassId, $categoryId);
        foreach ($operatorDataSet as $operatorData) {
            list($conditionData1, $conditionData2,,, $expected) = $operatorData;
            $data[] = [['operator_or'], $conditionData1, $conditionData2, $productClassId, $categoryId, $expected];
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
     * @param $ruleData
     * @param $conditionData1
     * @param $conditionData2
     * @param $promotionValue
     * @param $productClassId
     * @param $categoryId
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario1
     */
    public function testGetDiscount_Scenario1($ruleData, $conditionData1, $conditionData2, $productClassId, $categoryId, $promotionValue, $expectedValue)
    {
        $this->rule->setId(rand());
        $this->rule->setOperator($ruleData[0]);
        $this->rule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));

        $condition1 = new Condition\ProductClassIdCondition();
        $condition1->setId(rand());
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setOperator($conditionData1[0]);
        $condition1->setValue($conditionData1[1]);
        $this->rule->addConditions($condition1);

        $condition2 = new Condition\ProductCategoryIdCondition();
        $condition2->setId(rand());
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setOperator($conditionData2[0]);
        $condition2->setValue($conditionData2[1]);

        $this->rule->addConditions($condition2);

        $promotion = new Promotion\ProductClassPriceAmountPromotion();
        $promotion->setId(rand());
        $promotion->setValue($promotionValue);
        $this->rule->setPromotion($promotion);

        $ProductCategory = new ProductCategory();
        $ProductCategory->setCategoryId($categoryId);
        $Product = new Product();
        $Product->addProductCategory($ProductCategory);
        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => $productClassId]);
        $ProductClass->setProduct($Product);

        $actual = $this->rule->getDiscount($ProductClass);

        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals($expectedValue, $actual->getValue());
        $this->assertEquals($this->rule->getId(), $actual->getRuleId());
    }

    public function dataProvider_testGetDiscount_Scenario1($testMethod = null, $productClassId = 1, $categoryId = 2)
    {
        $data = [];
        $testMatchDataSet = static::dataProvider_testMatch_Scenario1(null, $productClassId, $categoryId);
        foreach ($testMatchDataSet as $testMatchData) {
            list($operator, $conditionData1, $conditionData2,,, $expected) = $testMatchData;
            foreach (PromotionTest\ProductClassPriceAmountPromotionTest::dataProvider_testGetDiscount_Scenario1() as $promotionData) {
                list($promotionValue, $promotionExpected) = $promotionData;
                $data[] = [$operator, $conditionData1, $conditionData2, $productClassId, $categoryId, $promotionValue, $expected ? $promotionExpected : 0];
            }
        }

        return $data;
    }

    /**
     * @param $ruleData
     * @param $conditionData1
     * @param $conditionData2
     * @param $promotionValue
     * @param $productClassId
     * @param $categoryId
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario2
     */
    public function testGetDiscount_Scenario2($ruleData, $conditionData1, $conditionData2, $productClassId, $categoryId, $promotionValue, $productPrice, $expectedValue)
    {
        $this->rule->setId(rand());
        $this->rule->setOperator($ruleData[0]);
        $this->rule->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));

        $condition1 = new Condition\ProductClassIdCondition();
        $condition1->setId(rand());
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setOperator($conditionData1[0]);
        $condition1->setValue($conditionData1[1]);
        $this->rule->addConditions($condition1);

        $condition2 = new Condition\ProductCategoryIdCondition();
        $condition2->setId(rand());
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setOperator($conditionData2[0]);
        $condition2->setValue($conditionData2[1]);

        $this->rule->addConditions($condition2);

        $promotion = new Promotion\ProductClassPricePercentPromotion();
        $promotion->setId(rand());
        $promotion->setValue($promotionValue);
        $this->rule->setPromotion($promotion);

        $ProductCategory = new ProductCategory();
        $ProductCategory->setCategoryId($categoryId);
        $Product = new Product();
        $Product->addProductCategory($ProductCategory);
        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => $productClassId]);
        $ProductClass->setProduct($Product);
        $ProductClass->setPrice02IncTax($productPrice);

        $actual = $this->rule->getDiscount($ProductClass);

        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals($expectedValue, $actual->getValue());
        $this->assertEquals($this->rule->getId(), $actual->getRuleId());
    }

    public function dataProvider_testGetDiscount_Scenario2($testMethod = null, $productClassId = 1, $categoryId = 2, $productPrice = 34567)
    {
        $data = [];
        $testMatchDataSet = static::dataProvider_testMatch_Scenario1(null, $productClassId, $categoryId);
        foreach ($testMatchDataSet as $testMatchData) {
            list($operator, $conditionData1, $conditionData2,,, $expected) = $testMatchData;
            foreach (PromotionTest\ProductClassPricePercentPromotionTest::dataProvider_testGetDiscount_Scenario1(null, $productPrice) as $promotionData) {
                list($promotionValue,, $promotionExpected) = $promotionData;
                $data[] = [$operator, $conditionData1, $conditionData2, $productClassId, $categoryId, $productPrice, $promotionValue, $expected ? $promotionExpected : 0];
            }
        }

        return $data;
    }
}
