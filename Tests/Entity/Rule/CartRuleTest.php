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

use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Cart;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Utils\Memoization;

/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class CartRuleTest extends EccubeTestCase
{
    /**
     * @var CartRule
     */
    protected $cartRule;

    public function setUp()
    {
        parent::setUp();

        $this->cartRule = new CartRule();
        $mMemoization = $this->getMockBuilder(Memoization::class)->getMock();
        $mMemoization->method('has')->willReturn(false);
        $this->cartRule->setMemoization($mMemoization);
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE,
        ];
        $this->actual = $this->cartRule->getOperatorTypes();
        $this->verify();
    }

    public function testGetConditionTypes()
    {
        $this->expected = [
            Condition\CartTotalCondition::TYPE,
        ];
        $this->actual = $this->cartRule->getConditionTypes();
        $this->verify();
    }

    public function testGetPromotionTypes()
    {
        $this->expected = [
            Promotion\CartTotalPercentPromotion::TYPE,
            Promotion\CartTotalAmountPromotion::TYPE,
        ];
        $this->actual = $this->cartRule->getPromotionTypes();
        $this->verify();
    }

    public function testMatch_Invalid()
    {
        $this->expected = false;
        $this->actual = $this->cartRule->match(new \stdClass());
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

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($mOperatorFactory);

        $this->expected = true;
        $this->actual = $this->cartRule->match(new Order());
        $this->verify();
    }

    public function testGetDiscountItemsFromOrder_Invalid()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(false);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($mOperatorFactory);

        $this->expected = [];
        $this->actual = $this->cartRule->getDiscountItemsFromOrder(new Order());
        $this->verify();
    }

    public function testGetDiscountItemsFromOrder_Valid()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($mOperatorFactory);

        $DiscountItems = [new OrderItem()];
        $mPromotion = $this->getMockBuilder(CartTotalPercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);

        $this->cartRule->setPromotion($mPromotion);

        $this->expected = $DiscountItems;
        $this->actual = $this->cartRule->getDiscountItemsFromOrder(new Order());
        $this->verify();
    }

    public function testGetDiscountItems_Invalid()
    {
        $this->expected = [];
        $this->actual = $this->cartRule->getDiscountItems(new \stdClass());
        $this->verify();
    }

    public function testGetDiscountItems_ValidCart()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($mOperatorFactory);

        $DiscountItems = [new OrderItem()];
        $mPromotion = $this->getMockBuilder(CartTotalPercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);

        $this->cartRule->setPromotion($mPromotion);

        $this->expected = $DiscountItems;
        $this->actual = $this->cartRule->getDiscountItems(new Cart());
        $this->verify();
    }

    public function testGetDiscountItems_ValidOrder()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);

        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($mOperatorFactory);

        $DiscountItems = [new OrderItem()];
        $mPromotion = $this->getMockBuilder(CartTotalPercentPromotion::class)->getMock();
        $mPromotion->expects($this->once())
            ->method('getDiscountItems')
            ->willReturn($DiscountItems);

        $this->cartRule->setPromotion($mPromotion);

        $this->expected = $DiscountItems;
        $this->actual = $this->cartRule->getDiscountItems(new Order());
        $this->verify();
    }
}
