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

namespace Plugin\FlashSale\Tests\Entity\Operator;

use Doctrine\Common\Collections\ArrayCollection;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Entity\Operator as Operator;

class InOperatorTest extends AbstractServiceTestCase
{
    /**
     * @var Operator\InOperator
     */
    protected $operator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->operator = new Operator\InOperator();
    }

    public function testGetName()
    {
        self::assertEquals('is one of', $this->operator->getName());
    }

    public function testGetType()
    {
        self::assertEquals('operator_in', $this->operator->getType());
    }

    public function testMatchScalarTypeTrue()
    {
        self::assertTrue($this->operator->match([1, 2, 3, 5, 6], 5));
        self::assertTrue($this->operator->match('1,2,3,5,6', 5));
    }

    public function testMatchScalarTypeFalse()
    {
        self::assertFalse($this->operator->match([1, 2, 3, 5, 6], 10));
    }

    public function testMatchConditionTrue()
    {
        $condition1 = $this->getMockBuilder(ProductClassIdCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        $condition1->method('match')->willReturn(true);
        $condition2 = $this->getMockBuilder(ProductClassIdCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        $condition2->method('match')->willReturn(true);
        $conditions = new ArrayCollection([$condition1, $condition2]);

        self::assertTrue($this->operator->match($conditions, 5));
    }

    public function testMatchConditionFalse()
    {
        $condition1 = $this->getMockBuilder(ProductClassIdCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        $condition1->method('match')->willReturn(false);
        $condition2 = $this->getMockBuilder(ProductClassIdCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        $condition2->method('match')->willReturn(false);
        $conditions = new ArrayCollection([$condition1, $condition2]);

        self::assertFalse($this->operator->match($conditions, 5));
    }

    public function testParseConditionAllOperator()
    {
        $Rule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $Rule->method('getOperator')->willReturn(Operator\AllOperator::TYPE);
        $Condition = $this->getMockBuilder(ProductClassIdCondition::class)->getMock();
        $Condition->method('getValue')->willReturn(1);
        $Condition->method('getRule')->willReturn($Rule);
        $qb = $this->entityManager->createQueryBuilder();
        $this->operator->parseCondition($qb, $Condition);

        self::assertEquals('pc.id IN(1)', (string)$qb->getDQLPart('where'));
    }

    public function testParseConditionEqualOperator()
    {
        $Rule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $Rule->method('getOperator')->willReturn(Operator\EqualOperator::TYPE);
        $Condition = $this->getMockBuilder(ProductClassIdCondition::class)->getMock();
        $Condition->method('getValue')->willReturn(1);
        $Condition->method('getRule')->willReturn($Rule);
        $qb = $this->entityManager->createQueryBuilder();
        $this->operator->parseCondition($qb, $Condition);

        self::assertEquals('pc.id IN(1)', (string)$qb->getDQLPart('where'));
    }

    public function testParseConditionInOperator()
    {
        $Rule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $Rule->method('getOperator')->willReturn(Operator\InOperator::TYPE);
        $Condition = $this->getMockBuilder(ProductClassIdCondition::class)->getMock();
        $Condition->method('getValue')->willReturn(1);
        $Condition->method('getRule')->willReturn($Rule);
        $qb = $this->entityManager->createQueryBuilder();
        $this->operator->parseCondition($qb, $Condition);

        self::assertEquals('pc.id IN(1)', (string)$qb->getDQLPart('where'));
    }

    public function testParseConditionNotEqualOperator()
    {
        $Rule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $Rule->method('getOperator')->willReturn(Operator\NotEqualOperator::TYPE);
        $Condition = $this->getMockBuilder(ProductClassIdCondition::class)->getMock();
        $Condition->method('getValue')->willReturn(1);
        $Condition->method('getRule')->willReturn($Rule);
        $qb = $this->entityManager->createQueryBuilder();
        $this->operator->parseCondition($qb, $Condition);

        self::assertEquals('pc.id NOT IN(1)', (string)$qb->getDQLPart('where'));
    }
}
