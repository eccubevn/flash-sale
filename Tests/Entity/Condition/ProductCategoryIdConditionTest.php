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

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductCategoryIdConditionTest extends EccubeTestCase
{
    /**
     * @var ProductCategoryIdCondition
     */
    protected $productCategoryIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->productCategoryIdCondition = new ProductCategoryIdCondition();
        $this->productCategoryIdCondition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotInOperator::TYPE,
        ];
        $this->actual = $this->productCategoryIdCondition->getOperatorTypes();
        $this->verify();
    }

    public function testMatch_Scenario0()
    {
        $actual = $this->productCategoryIdCondition->match(new \stdClass());
        $this->assertEquals(false, $actual);
    }

    /**
     * @param $conditionOperator
     * @param $conditionValue
     * @param $categoryIds
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($conditionOperator, $conditionValue, $categoryIds, $expected)
    {
        $this->productCategoryIdCondition->setValue($conditionValue);
        $this->productCategoryIdCondition->setOperator($conditionOperator);


        $Product = new Product();
        foreach ($categoryIds as $categoryId) {
            $ProductCategory = new ProductCategory();
            $ProductCategory->setCategoryId($categoryId);
            $Product->addProductCategory($ProductCategory);
        }
        $ProductClass = new ProductClass();
        $ProductClass->setProduct($Product);

        $actual = $this->productCategoryIdCondition->match($ProductClass);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario1()
    {
        return [
            ['operator_in', '1,2,3,4,5', [5], true],
            ['operator_in', '1,2,3,4,5', [10], false],
            ['operator_in', '1,2,3,4,5', ['10'], false],
            ['operator_not_in', '1,2,3,4,5', [5], false],
            ['operator_not_in', '1,2,3,4,5', ['5'], false],
            ['operator_not_in', '1,2,3,4,5', [10], true],
            ['operator_not_in', '1,2,3,4,5', ['10', '7'], true],
        ];
    }
}
