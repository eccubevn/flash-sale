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

use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class ProductClassRuleTest extends AbstractEntityTest
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
        $this->productClassRule = new ProductClassRule();
        $this->productClassRule->setId(1);
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

    public function testMatch_Invalid()
    {
        $this->assertFalse($this->productClassRule->match(new \stdClass()));
    }

    public function testMatch()
    {
        $this->expected = true;
        $ProductClass = new ProductClass();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn($this->expected);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($operatorFactory);
        $this->actual = $this->productClassRule->match($ProductClass);
        $this->verify();
    }

    public function testGetDiscount_InvalidType()
    {
        $discount = $this->productClassRule->getDiscount(new \stdClass());
        $this->assertEmpty($discount->getValue());
        $this->assertEquals(1, $discount->getRuleId());
    }

    public function testGetDiscount_InvalidMatch()
    {
        $ProductClass = new ProductClass();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(false);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($operatorFactory);
        $discount = $this->productClassRule->getDiscount($ProductClass);
        $this->assertEmpty($discount->getValue());
        $this->assertEquals($this->productClassRule->getId(), $discount->getRuleId());
    }

    public function testGetDiscount_Valid()
    {
        $discountValue = 200;
        $discount = new Discount();
        $discount->setValue($discountValue);
        $ProductClass = new ProductClass();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn(true);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);
        $promotion = $this->getMockBuilder(Promotion\CartTotalPercentPromotion::class)->getMock();
        $promotion->method('getDiscount')->willReturn($discount);

        $this->productClassRule->setPromotion($promotion);
        $this->productClassRule->setOperator(Operator\InOperator::TYPE);
        $this->productClassRule->setOperatorFactory($operatorFactory);
        $discount = $this->productClassRule->getDiscount($ProductClass);

        $this->assertEquals($discountValue, $discount->getValue());
        $this->assertEquals($this->productClassRule->getId(), $discount->getRuleId());
    }
}
