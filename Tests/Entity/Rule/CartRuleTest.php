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

use Eccube\Tests\EccubeTestCase;
use Eccube\Entity\Order;
use Eccube\Entity\Cart;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Factory\OperatorFactory;

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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->cartRule = new CartRule();
        $this->cartRule->setId(1);
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
            Condition\CartTotalCondition::TYPE
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
        $this->assertFalse($this->cartRule->match(new \stdClass()));
    }

    public function testMatch()
    {
        $this->expected = true;
        $Order = new Order();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn($this->expected);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($operatorFactory);
        $this->actual = $this->cartRule->match($Order);
        $this->verify();
    }

    public function testGetDiscount_InvalidType()
    {
        $discount = $this->cartRule->getDiscount(new \stdClass());
        $this->assertEmpty($discount->getValue());
        $this->assertEquals($this->cartRule->getId(), $discount->getRuleId());
    }

    public function testGetDiscount_InvalidCart()
    {
        $Cart = new Cart();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(false);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($operatorFactory);
        $discount = $this->cartRule->getDiscount($Cart);
        $this->assertEmpty($discount->getValue());
        $this->assertEquals($this->cartRule->getId(), $discount->getRuleId());
    }

    public function testGetDiscount_ValidCart()
    {
        $discountValue = 200;
        $discount = new Discount();
        $discount->setValue($discountValue);
        $Cart = new Cart();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(true);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);
        $promotion = $this->getMockBuilder(Promotion\CartTotalPercentPromotion::class)->getMock();
        $promotion->method('getDiscount')->willReturn($discount);

        $this->cartRule->setPromotion($promotion);
        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($operatorFactory);
        $discount = $this->cartRule->getDiscount($Cart);
        
        $this->assertEquals($discountValue, $discount->getValue());
        $this->assertEquals($this->cartRule->getId(), $discount->getRuleId());
    }

    public function testGetDiscount_InvalidOrder()
    {
        $Order = new Order();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(false);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($operatorFactory);
        $discount = $this->cartRule->getDiscount($Order);
        $this->assertEmpty($discount->getValue());
        $this->assertEquals($this->cartRule->getId(), $discount->getRuleId());
    }

    public function testGetDiscount_ValidOrder()
    {
        $discountValue = 300;
        $discount = new Discount();
        $discount->setValue($discountValue);
        $Order = new Order();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(true);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);
        $promotion = $this->getMockBuilder(Promotion\CartTotalPercentPromotion::class)->getMock();
        $promotion->method('getDiscount')->willReturn($discount);

        $this->cartRule->setPromotion($promotion);
        $this->cartRule->setOperator(Operator\InOperator::TYPE);
        $this->cartRule->setOperatorFactory($operatorFactory);
        $discount = $this->cartRule->getDiscount($Order);

        $this->assertEquals($discountValue, $discount->getValue());
        $this->assertEquals($this->cartRule->getId(), $discount->getRuleId());
    }
}
