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
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductClassIdConditionTest extends AbstractEntityTest
{
    /**
     * @var ProductClassIdCondition
     */
    protected $productClassIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->productClassIdCondition = new ProductClassIdCondition();
    }

    public function testMatch_Invalid()
    {
        $this->assertFalse($this->productClassIdCondition->match(new \stdClass()));
    }

    public function testMatch()
    {
        $this->expected = true;
        $ProductClass = new ProductClass();
        $operator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $operator->method('match')->willReturn($this->expected);
        $operatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $operatorFactory->method('create')->willReturn($operator);

        $this->productClassIdCondition->setOperator(Operator\InOperator::TYPE);
        $this->productClassIdCondition->setOperatorFactory($operatorFactory);
        $this->actual = $this->productClassIdCondition->match($ProductClass);
        $this->verify();
    }
}
