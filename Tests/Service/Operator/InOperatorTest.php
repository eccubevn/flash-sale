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

class InOperatorTest extends EccubeTestCase
{
    use InOperatorDataProviderTrait;

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
     * @param $method
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($method)
    {
        list($condition, $data, $expected) = $this->$method();
        $actual = $this->inOperator->match($condition, $data);
        $this->assertEquals($expected, $actual);
    }

    public function dataProvider_testMatch()
    {
        return [
            'true#1' => ['dataProvider_testMatch_True1'],
            'true#2' => ['dataProvider_testMatch_True2'],
            'false#1' => ['dataProvider_testMatch_False1'],
            'false#2' => ['dataProvider_testMatch_False2'],
        ];
    }
}
