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
use Plugin\FlashSale\Service\Operator\GreaterThanOperator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\GreaterThanOperatorDataProvider;

class GreaterThanOperatorTest extends EccubeTestCase
{
    /**
     * @var GreaterThanOperator
     */
    protected $greaterThanOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->greaterThanOperator = new GreaterThanOperator();
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
        $actual = $this->greaterThanOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [1, 2, true],
            [100, 2, false],
        ];
    }
}
