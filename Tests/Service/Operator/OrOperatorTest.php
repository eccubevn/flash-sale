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

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Service\Operator as Operator;

class OrOperatorTest extends EccubeTestCase
{
    /**
     * @var Operator\OrOperator
     */
    protected $orOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->markTestIncomplete();
        parent::setUp();

        $this->orOperator = new Operator\OrOperator();
    }


    /**
     * @param $dataSet
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $this->$dataSet();
        $result = $this->orOperator->match($condition, $data);
        $this->assertEquals($expected, $result);
    }

    public function dataProvider_testMatch()
    {
        return [
            'true#1' => []
        ];
    }
}
