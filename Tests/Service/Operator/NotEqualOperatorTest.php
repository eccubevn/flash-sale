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
use Plugin\FlashSale\Service\Operator\NotEqualOperator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\NotEqualOperatorDataProvider;

class NotEqualOperatorTest extends EccubeTestCase
{

    /**
     * @var NotEqualOperator
     */
    protected $notEqualOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->notEqualOperator = new NotEqualOperator();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $dataSet;
        $actual = $this->notEqualOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [NotEqualOperatorDataProvider::testMatch_True1()],
            [NotEqualOperatorDataProvider::testMatch_True2()],
            [NotEqualOperatorDataProvider::testMatch_False1()],
            [NotEqualOperatorDataProvider::testMatch_False2()],
        ];
    }
}
