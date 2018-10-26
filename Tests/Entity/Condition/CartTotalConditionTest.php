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

use Eccube\Entity\Order;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Factory\ConditionFactory;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends EccubeTestCase
{
    /**
     * @var Product
     */
    protected $Product;

    /**
     * @var ProductClass
     */
    protected $ProductClass1;

    /**
     * @var Condition\CartTotalCondition
     */
    protected $cartTotalCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $ConditionFactory = new ConditionFactory();
        $this->cartTotalCondition = $ConditionFactory->create([
            'type' => Condition\CartTotalCondition::TYPE,
            'value' => 100,
            'operator' => Operator\GreaterThanOperator::TYPE
        ]);
        $this->cartTotalCondition->setId(1);
    }

    public function testMatch_Invalid_NotOrderType()
    {
        self::assertFalse($this->cartTotalCondition->match(new \stdClass()));
    }

    public function testOperatorTypes()
    {
        $this->expected = [
            Operator\GreaterThanOperator::TYPE,
            Operator\EqualOperator::TYPE,
            Operator\LessThanOperator::TYPE
        ];
        $this->actual = $this->cartTotalCondition->getOperatorTypes();
        $this->verify();
    }

    public function testMatch_OrderType()
    {
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);
        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);

        $this->cartTotalCondition->setOperator(Operator\InOperator::TYPE);
        $this->cartTotalCondition->setOperatorFactory($mOperatorFactory);

        self::assertTrue($this->cartTotalCondition->match(new Order()));
    }
}
