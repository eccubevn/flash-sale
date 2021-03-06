<?php

/*
 * This file is part of the Flash Sale plugin
 *
 * Copyright(c) ECCUBE VN LAB. All Rights Reserved.
 *
 * https://www.facebook.com/groups/eccube.vn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Tests\Service\Operator;

use Plugin\FlashSale\Tests\Service\AbstractOperatorTest;
use Plugin\FlashSale\Service\Operator\NotEqualOperator;

class NotEqualOperatorTest extends AbstractOperatorTest
{
    /**
     * @var NotEqualOperator
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

    /**
     * @param $condition
     * @param $data
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($condition, $data, $expected)
    {
        $actual = $this->operator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch($data = 12345)
    {
        return [
            [(int) $data - 1, $data, true],
            [$data, $data, false],
        ];
    }
}
