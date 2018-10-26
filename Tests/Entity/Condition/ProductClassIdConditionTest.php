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
class ProductClassIdConditionTest extends EccubeTestCase
{
    /**
     * @var Condition\ProductClassIdCondition
     */
    protected $productClassIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $ConditionFactory = new ConditionFactory();
        $this->productClassIdCondition = $ConditionFactory->create([
            'type' => Condition\ProductClassIdCondition::TYPE,
            'value' => 100,
            'operator' => Operator\InOperator::TYPE
        ]);
        $this->productClassIdCondition->setId(1);
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotEqualOperator::TYPE,
        ];
        $this->actual = $this->productClassIdCondition->getOperatorTypes();
        $this->verify();
    }

    public function testMatch_Invalid()
    {
        $this->expected = false;
        $this->actual = $this->productClassIdCondition->match(new \stdClass());
        $this->verify();
    }

    public function testMatch_Valid()
    {
        $ProductClass = new ProductClass();
        $mOperator = $this->getMockBuilder(Operator\InOperator::class)->getMock();
        $mOperator->expects($this->once())
            ->method('match')
            ->willReturn(true);
        $mOperatorFactory = $this->getMockBuilder(OperatorFactory::class)->getMock();
        $mOperatorFactory->expects($this->once())
            ->method('create')
            ->willReturn($mOperator);
        $this->productClassIdCondition->setOperator(Operator\InOperator::TYPE);
        $this->productClassIdCondition->setOperatorFactory($mOperatorFactory);

        $this->expected = true;
        $this->actual = $this->productClassIdCondition->match($ProductClass);
    }
}
