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
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Service\Operator as Operator;

class AllOperatorTest extends AbstractServiceTestCase
{
    use AllOperatorDataProviderTrait;

    /**
     * @var Operator\AllOperator
     */
    protected $allOperator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->markTestIncomplete();
        parent::setUp();

        $this->allOperator = new Operator\AllOperator();
    }


    /**
     * @param $dataSet
     */
    public function testMatch($dataSet)
    {
        list($condition, $data, $expected) = $this->$dataSet();
        $result = $this->allOperator->match($condition, $data);
        $this->assertEquals($expected, $result);
    }

    public function dataProvider_testMatch()
    {
        return [
            'true#1' => []
        ];
    }
}
