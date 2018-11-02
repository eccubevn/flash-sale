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
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\EqualOperatorDataProvider;
use Plugin\FlashSale\Service\Operator\EqualOperator;

class EqualOperatorTest extends EccubeTestCase
{
    /**
     * @var EqualOperator
     */
    protected $equalOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->equalOperator = new EqualOperator();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $dataSet;
        $actual = $this->equalOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [EqualOperatorDataProvider::testMatch_True1()],
            [EqualOperatorDataProvider::testMatch_True2()],
            [EqualOperatorDataProvider::testMatch_False1()],
            [EqualOperatorDataProvider::testMatch_False2()],
        ];
    }
}
