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
     * @param $condition
     * @param $data
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($condition, $data, $expected)
    {
        $actual = $this->notEqualOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            [123, '321', true],
            [456, 0, true],
            [789, '789', false],
            [1011, 1011, false]
        ];
    }
}
