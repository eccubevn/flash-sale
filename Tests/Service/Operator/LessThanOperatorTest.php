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

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Service\Operator\LessThanOperator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\LessThanOperatorDataProvider;

class LessThanOperatorTest extends EccubeTestCase
{

    /**
     * @var LessThanOperator
     */
    protected $lessThanOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->lessThanOperator = new LessThanOperator();
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
        $actual = $this->lessThanOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch($data = 12345)
    {
        return [
            [(int)$data+1, $data, true],
            [(int)$data-1, $data, false],
            [$data, $data, false],
        ];
    }
}
