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
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\InOperatorDataProvider;

class InOperatorTest extends EccubeTestCase
{
    /**
     * @var InOperator
     */
    protected $inOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->inOperator = new InOperator();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $dataSet;
        $actual = $this->inOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [InOperatorDataProvider::testMatch_True1()],
            [InOperatorDataProvider::testMatch_True2()],
            [InOperatorDataProvider::testMatch_False1()],
            [InOperatorDataProvider::testMatch_False2()],
        ];
    }
}
