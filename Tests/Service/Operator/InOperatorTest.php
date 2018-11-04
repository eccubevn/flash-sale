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
     * @param $condition
     * @param $data
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($condition, $data, $expected)
    {
        $actual = $this->inOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch($data = 1)
    {
        return [
            [$data.','.((int)$data-1), $data, true],
            [[$data,(int)$data+1], [$data], true],
            [[(int)$data-1,(int)$data+1], $data, false],
            [((int)$data-1).','.((int)$data+1), [$data], false],
        ];
    }
}
