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

namespace Plugin\FlashSale\Service\Operator;

use Doctrine\Common\Collections\ArrayCollection;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Entity\Rule;

class AllOperatorTest extends AbstractServiceTestCase
{
    /**
     * @var AllOperator
     */
    protected $operator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->operator = new AllOperator();
    }

    public function testGetName()
    {
        self::assertEquals('is all of', $this->operator->getName());
    }

    public function testGetType()
    {
        self::assertEquals(AllOperator::TYPE, $this->operator->getType());
    }

    public function testMatchScalarTypeTrue()
    {
        self::assertTrue($this->operator->match([5, 5, 5, 5, 5], 5));
        self::assertTrue($this->operator->match('5,5,5,5,5', 5));
    }

    public function testMatchScalarTypeFalse()
    {
        self::assertFalse($this->operator->match([1, 2, 3, 5, 6], 10));
        self::assertFalse($this->operator->match('1,2,3,4,5,6', 10));
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
        $condition1->method('match')->willReturn(true);
        $condition2 = $this->getMockBuilder(ProductClassIdCondition::class)
            ->disableOriginalConstructor()
            ->getMock();
        $condition2->method('match')->willReturn(false);
        $conditions = new ArrayCollection([$condition1, $condition2]);

        self::assertFalse($this->operator->match($conditions, 5));
    }
}
