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
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends AbstractEntityTest
{
    /**
     * @var CartTotalCondition
     */
    protected $cartTotalCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->cartTotalCondition = new CartTotalCondition();
    }

    public function testMatch_Invalid()
    {
        self::assertFalse($this->cartTotalCondition->match(new \stdClass()));
    }

    public function testMatch()
    {
        $this->expected = true;
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn($this->expected);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);
        $Order = new Order();

        $this->cartTotalCondition->setOperator(Operator\InOperator::TYPE);
        $this->cartTotalCondition->setOperatorFactory($operatorFactory);
        $this->actual = $this->cartTotalCondition->match($Order);
        $this->verify();
    }
}
