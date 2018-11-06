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

namespace Plugin\FlashSale\Tests\Service\Operator;

use Plugin\FlashSale\Tests\Service\AbstractOperatorTest;
use Plugin\FlashSale\Service\Operator\EqualOperator;

class EqualOperatorTest extends AbstractOperatorTest
{
    /**
     * @var EqualOperator
     */
    protected $operator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->operator = new EqualOperator();
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

    public static function dataProvider_testMatch($data = 12345)
    {
        return [
            [$data, $data, true],
            [(int)$data - 1, $data, false],
            [null, $data, false]
        ];
    }
}
