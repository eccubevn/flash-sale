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

use Eccube\Entity\OrderItem;
use Eccube\Entity\Order;
use Eccube\Entity\ProductClass;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Utils\Memoization;


/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class ProductClassRuleTest extends EccubeTestCase
{
    /**
     * @var ProductClassRule
     */
    protected $productClassRule;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $mMemoization = $this->getMockBuilder(Memoization::class)->getMock();
        $mMemoization->method('has')->willReturn(false);
        $this->productClassRule = new ProductClassRule();
        $this->productClassRule->setMemoization($mMemoization);
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE,
        ];
        $this->actual = $this->productClassRule->getOperatorTypes();
        $this->verify();
    }

    public function testGetConditionTypes()
    {
        $this->expected = [
            Condition\ProductClassIdCondition::TYPE,
            Condition\ProductCategoryIdCondition::TYPE,
        ];
        $this->actual = $this->productClassRule->getConditionTypes();
        $this->verify();
    }

    public function testGetPromotionTypes()
    {
        $this->expected = [
            Promotion\ProductClassPricePercentPromotion::TYPE,
            Promotion\ProductClassPriceAmountPromotion::TYPE,
        ];
        $this->actual = $this->productClassRule->getPromotionTypes();
        $this->verify();
    }

    protected function match($expected, $actual)
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn($expected);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $this->expected = $expected;
        $this->actual = $actual;
        $this->verify();
    }

    public function testMatch_Invalid()
    {
        $this->expected = false;
        $this->actual = $this->productClassRule->match(new \stdClass());
        $this->verify();
    }

    public function testMatch_Valid()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $this->expected = true;
        $this->actual = $this->productClassRule->match(new ProductClass());
        $this->verify();
    }

    public function testGetDiscountItems_InvalidByType()
    {
        $this->expected = [];
        $this->actual = $this->productClassRule->getDiscountItems(new \stdClass());
        $this->verify();
    }

    public function testGetDiscountItems_ProductClass_Invalid()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(false);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $this->expected = [];
        $this->actual = $this->productClassRule->getDiscountItems(new ProductClass());
        $this->verify();
    }

    public function testGetDiscountItems_ProductClass()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $DiscountItems = [new OrderItem()];

        $mPromotion = $this->getMockBuilder(ProductClassPricePercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);
        $this->productClassRule->setPromotion($mPromotion);

        $this->expected = $DiscountItems;
        $this->actual = $this->productClassRule->getDiscountItems(new ProductClass());
        $this->verify();
    }

    public function testGetDiscountItems_OrderItem_Invalid()
    {
        $OrderItem = new OrderItem();
        $OrderItem->setQuantity(5);
        $OrderItem->setProductClass(new ProductClass());
        $OrderProductType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $OrderItem->setOrderItemType($OrderProductType);

        $result = $this->productClassRule->getDiscountItems($OrderItem);

        self::assertEquals([], $result);
    }

    public function testGetDiscountItems_OrderItem()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $OrderItem = new OrderItem();
        $OrderItem->setQuantity(5);
        $OrderItem->setProductClass(new ProductClass());
        $OrderProductType = $this->entityManager->find(OrderItemType::class, OrderItemType::PRODUCT);
        $OrderItem->setOrderItemType($OrderProductType);

        $DiscountItems = [new OrderItem()];

        $mPromotion = $this->getMockBuilder(ProductClassPricePercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);
        $this->productClassRule->setPromotion($mPromotion);

        $result = $this->productClassRule->getDiscountItems($OrderItem);

        self::assertEquals($OrderItem->getQuantity(), $result[0]->getQuantity());
    }

    public function testGetDiscountItems_Order()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($mOperatorFactory);

        $OrderItem = new OrderItem();
        $OrderItem->setQuantity(5);
        $OrderItem->setProductClass(new ProductClass());
        $OrderProductType = $this->entityManager->find(OrderItemType::class, OrderItemType::PRODUCT);
        $OrderItem->setOrderItemType($OrderProductType);
        $Order = $this->getMockBuilder(Order::class)->disableOriginalConstructor()->getMock();
        $Order->method('getItems')->willReturn([$OrderItem]);

        $DiscountItems = [new OrderItem()];

        $mPromotion = $this->getMockBuilder(ProductClassPricePercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);
        $this->productClassRule->setPromotion($mPromotion);

        $result = $this->productClassRule->getDiscountItems($Order);

        self::assertEquals($OrderItem->getQuantity(), $result[0]->getQuantity());
    }
}
