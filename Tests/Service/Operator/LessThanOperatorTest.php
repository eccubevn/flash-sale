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

use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class LessThanOperatorTest extends AbstractServiceTestCase
{
    /**
     * @var LessThanOperator
     */
    protected $operator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->operator = new LessThanOperator();
    }

    public function testGetName()
    {
        self::assertEquals('is less than to', $this->operator->getName());
    }

    public function testGetType()
    {
        self::assertEquals(LessThanOperator::TYPE, $this->operator->getType());
    }

    public function testMatchScalarTypeTrue()
    {
        self::assertTrue($this->operator->match(6, 5));
    }

    public function testMatchScalarTypeFalse()
    {
        self::assertFalse($this->operator->match(4, 5));
    }
}