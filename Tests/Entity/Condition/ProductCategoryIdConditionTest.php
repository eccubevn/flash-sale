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

use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\ConditionTest;
use Plugin\FlashSale\Tests\Service\Operator as OperatorTest;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductCategoryIdConditionTest extends ConditionTest
{
    /**
     * @var ProductCategoryIdCondition
     */
    protected $condition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->condition = new ProductCategoryIdCondition();
        $this->condition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public static function dataProvider_testRawData_Valid()
    {
        return [
            [['id' => 1, 'type' => 'condition_product_category_id', 'operator' => 'operator_in', 'value' => '1,2']],
            [['id' => 2, 'type' => 'condition_product_category_id', 'operator' => 'operator_not_in', 'value' => '3,4']],
        ];
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotInOperator::TYPE,
        ];
        $this->actual = $this->condition->getOperatorTypes();
        $this->verify();
    }

    public function testMatch_Invalid()
    {
        $actual = $this->condition->match(new \stdClass());
        $this->assertEquals(false, $actual);
    }

    /**
     * @param $conditionData
     * @param $ProductClass
     * @param $expected
     * @dataProvider dataProvider_testMatch_Valid
     */
    public function testMatch_Valid($conditionData, $ProductClass, $expected)
    {
        list($conditionOperator, $conditionValue) = $conditionData;
        $this->condition->setId(rand());
        $this->condition->setValue($conditionValue);
        $this->condition->setOperator($conditionOperator);

        $actual = $this->condition->match($ProductClass);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Valid($testMethod = null, $categoryId = 1)
    {
        $data = [];
        foreach (OperatorTest\InOperatorTest::dataProvider_testMatch($categoryId) as $operatorData) {
            list($conditionValue,, $expected) = $operatorData;
            if (is_array($conditionValue)) {
                continue;
            }

            $Product = new Product();
            $ProductCategory = new ProductCategory();
            $ProductCategory->setCategoryId($categoryId);
            $Product->addProductCategory($ProductCategory);
            $ProductClass = new ProductClass();
            $ProductClass->setProduct($Product);

            $data[] = [['operator_in', (string)$conditionValue], $ProductClass, $expected];
        }

        foreach (OperatorTest\NotInOperatorTest::dataProvider_testMatch($categoryId) as $operatorData) {
            list($conditionValue,, $expected) = $operatorData;
            if (is_array($conditionValue)) {
                continue;
            }

            $Product = new Product();
            $ProductCategory = new ProductCategory();
            $ProductCategory->setCategoryId($categoryId);
            $Product->addProductCategory($ProductCategory);
            $ProductClass = new ProductClass();
            $ProductClass->setProduct($Product);

            $data[] = [['operator_not_in', (string)$conditionValue], $ProductClass, $expected];
        }

        return $data;
    }
}
