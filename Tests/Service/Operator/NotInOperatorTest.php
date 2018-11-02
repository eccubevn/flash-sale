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
use Plugin\FlashSale\Service\Operator\NotInOperator;
use Plugin\FlashSale\Tests\DataProvider\Service\Operator\NotInOperatorDataProvider;

class NotInOperatorTest extends EccubeTestCase
{
    /**
     * @var NotInOperator
     */
    protected $notInOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->notInOperator = new NotInOperator();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $dataSet;
        $actual = $this->notInOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [NotInOperatorDataProvider::testMatch_True1()],
            [NotInOperatorDataProvider::testMatch_True2()],
            [NotInOperatorDataProvider::testMatch_False1()],
            [NotInOperatorDataProvider::testMatch_False2()],
        ];
    }
}
