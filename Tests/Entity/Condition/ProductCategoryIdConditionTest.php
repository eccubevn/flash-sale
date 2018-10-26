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
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Factory\ConditionFactory;
use Plugin\FlashSale\Entity\Condition as Condition;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductCategoryIdConditionTest extends EccubeTestCase
{
    /**
     * @var Condition\ProductCategoryIdCondition
     */
    protected $productCategoryIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $ConditionFactory = new ConditionFactory();
        $this->productCategoryIdCondition = $ConditionFactory->create([
            'type' => Condition\ProductCategoryIdCondition::TYPE,
            'value' => 100,
            'operator' => Operator\InOperator::TYPE
        ]);
        $this->productCategoryIdCondition->setId(1);
    }

    public function testMatch_Invalid_NotProductClassType()
    {
        $this->expected = false;
        $this->actual = $this->productCategoryIdCondition->match(new \stdClass());
        $this->verify();
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotEqualOperator::TYPE,
        ];
        $this->actual = $this->productCategoryIdCondition->getOperatorTypes();
        $this->verify();
    }

    public function testMatch_Valid()
    {
        $mProductCategory = $this->getMockBuilder(ProductCategory::class)->getMock();
        $mProductCategory->expects($this->once())
            ->method('getCategoryId')
            ->willReturn(1);

        $mProduct = $this->getMockBuilder(Product::class)->getMock();
        $mProduct->expects($this->once())
            ->method('getProductCategories')
            ->willReturn([$mProductCategory]);

        $mProductClass = $this->getMockBuilder(ProductClass::class)->getMock();
        $mProductClass->expects($this->once())
            ->method('getProduct')
            ->willReturn($mProduct);

        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);
        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);
        $this->productCategoryIdCondition->setOperator(Operator\InOperator::TYPE);
        $this->productCategoryIdCondition->setOperatorFactory($mOperatorFactory);

        $this->expected = true;
        $this->actual = $this->productCategoryIdCondition->match($mProductClass);
        $this->verify();
    }
}
