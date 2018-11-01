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

use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class NotEqualOperatorTest extends AbstractServiceTestCase
{
    /**
     * @var InOperator
     */
    protected $operator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->operator = new NotEqualOperator();
    }

    public function testGetName()
    {
        self::assertEquals('is not equal to', $this->operator->getName());
    }

    public function testGetType()
    {
        self::assertEquals(NotEqualOperator::TYPE, $this->operator->getType());
    }

    public function testMatchScalarTypeTrue()
    {
        self::assertTrue($this->operator->match(5, 10));
    }

    public function testMatchScalarTypeFalse()
    {
        self::assertFalse($this->operator->match(10, 10));
    }
}
