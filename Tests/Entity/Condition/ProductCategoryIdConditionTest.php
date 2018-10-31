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
use Eccube\Entity\ProductClass;
use Eccube\Entity\ProductCategory;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;


/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductCategoryIdConditionTest extends AbstractEntityTest
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
        $this->productCategoryIdCondition->setId(1);
    }

    public function testMatch_Invalid()
    {
        self::assertFalse($this->productCategoryIdCondition->match(new \stdClass()));
    }

    public function testMatch_Valid()
    {
        $ProductCategory = $this->getMockBuilder(ProductCategory::class)->getMock();
        $Product = $this->getMockBuilder(Product::class)->getMock();
        $Product->method('getProductCategories')->willReturn([$ProductCategory]);
        $ProductClass = $this->getMockBuilder(ProductClass::class)->getMock();
        $ProductClass->method('getProduct')->willReturn($Product);

        $this->expected = true;
        $Operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $Operator->method('match')->willReturn($this->expected);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($Operator);
        $this->productCategoryIdCondition->setOperatorFactory($operatorFactory);
        $this->productCategoryIdCondition->setOperator(Operator\InOperator::TYPE);

        $this->actual = $this->productCategoryIdCondition->match($ProductClass);
        $this->verify();
    }
}
