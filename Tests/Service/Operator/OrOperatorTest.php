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
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Tests\Entity\Condition as ConditionTest;

class OrOperatorTest extends EccubeTestCase
{
    /**
     * @var Operator\OrOperator
     */
    protected $orOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->orOperator = new Operator\OrOperator();
    }

    public function testMatch_Scenario0()
    {
        $this->assertEquals(false, $this->orOperator->match(null, new \stdClass()));
        $this->assertEquals(false, $this->orOperator->match([new \stdClass()], new \stdClass()));

    }

    /**
     * @param $condition1Data
     * @param $condition2Data
     * @param $orderSubTotal
     * @param $expected
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($condition1Data, $condition2Data, $orderSubTotal, $expected)
    {
        $condition1 = new CartTotalCondition();
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setId(rand());
        $condition1->setOperator($condition1Data[0]);
        $condition1->setValue($condition1Data[1]);

        $condition2 = new CartTotalCondition();
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setId(rand());
        $condition2->setOperator($condition2Data[0]);
        $condition2->setValue($condition2Data[1]);

        $Order = new Order();
        $Order->setSubtotal($orderSubTotal);

        $conditions = [$condition1, $condition2];

        $actual = $this->orOperator->match($conditions, $Order);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario1($testMethod = null, $orderSubtotal = 12345)
    {
        $data = [];

        $conditionDataSet = ConditionTest\CartTotalConditionTest::dataProvider_testMatch_Scenario1();
        for ($i =0; $i < count($conditionDataSet); $i++) {
            for ($j=0; $j<count($conditionDataSet); $j++) {
                list($operatorI, $conditionValueI,, $expectedI) = $conditionDataSet[$i];
                list($operatorJ, $conditionValueJ,, $expectedJ) = $conditionDataSet[$j];
                $expected = $expectedI || $expectedJ;
                $data[] = [[$operatorI, $conditionValueI], [$operatorJ, $conditionValueJ], $orderSubtotal, $expected];
            }
        }

        return $data;
    }

    /**
     * @param $condition1Data
     * @param $condition2Data
     * @param $productClassId
     * @param $categoryId
     * @param $expected
     * @dataProvider dataProvider_testMatch_Scenario2
     */
    public function testMatch_Scenario2($condition1Data, $condition2Data, $productClassId, $categoryId, $expected)
    {
        $condition1 = new ProductClassIdCondition();
        $condition1->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition1->setId(rand());
        $condition1->setOperator($condition1Data[0]);
        $condition1->setValue($condition1Data[1]);

        $condition2 = new ProductCategoryIdCondition();
        $condition2->setOperatorFactory($this->container->get(Operator\OperatorFactory::class));
        $condition2->setId(rand());
        $condition2->setOperator($condition2Data[0]);
        $condition2->setValue($condition2Data[1]);

        $ProductCategory = new ProductCategory();
        $ProductCategory->setCategoryId($categoryId);
        $Product = new Product();
        $Product->addProductCategory($ProductCategory);
        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => $productClassId]);
        $ProductClass->setProduct($Product);

        $conditions = [$condition1, $condition2];

        $actual = $this->orOperator->match($conditions, $ProductClass);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario2($testMethod = null, $productClassId = 1, $categoryId = 2)
    {
        $data = [];

        $conditionDataSetI = ConditionTest\ProductClassIdConditionTest::dataProvider_testMatch_Scenario1(null, $productClassId);
        for ($i =0; $i < count($conditionDataSetI); $i++) {
            $conditionDataSetJ = ConditionTest\ProductCategoryIdConditionTest::dataProvider_testMatch_Scenario1(null, $categoryId);
            for ($j=0; $j<count($conditionDataSetJ); $j++) {
                list($operatorI, $conditionValueI,, $expectedI) = $conditionDataSetI[$i];
                list($operatorJ, $conditionValueJ,, $expectedJ) = $conditionDataSetJ[$j];
                $expected = $expectedI || $expectedJ;
                $data[] = [[$operatorI, $conditionValueI], [$operatorJ, $conditionValueJ], $productClassId , $categoryId, $expected];
            }
        }

        return $data;
    }
}
