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

namespace Plugin\FlashSale\Tests\Entity\Condition;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\DataProvider\Entity\Condition\ProductClassIdConditionDataProvider;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductClassIdConditionTest extends EccubeTestCase
{
    /**
     * @var ProductClassIdCondition
     */
    protected $productClassIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->productClassIdCondition = new ProductClassIdCondition();
        $this->productClassIdCondition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotInOperator::TYPE,
        ];
        $this->actual = $this->productClassIdCondition->getOperatorTypes();
        $this->verify();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($conditionData, $data, $expected) = $dataSet;

        $this->productClassIdCondition->setId($conditionData['id']);
        $this->productClassIdCondition->setValue($conditionData['value']);
        $this->productClassIdCondition->setOperator($conditionData['operator']);
        $result = $this->productClassIdCondition->match($data);
        $this->assertEquals($expected, $result);
    }

    public static function dataProvider_testMatch()
    {
        return [
            [ProductClassIdConditionDataProvider::testMatch_False1()],
            [ProductClassIdConditionDataProvider::testMatch_InOperator_True1()],
            [ProductClassIdConditionDataProvider::testMatch_InOperator_True2()],
            [ProductClassIdConditionDataProvider::testMatch_InOperator_False1()],
            [ProductClassIdConditionDataProvider::testMatch_InOperator_False2()],
            [ProductClassIdConditionDataProvider::testMatch_NotInOperator_True1()],
            [ProductClassIdConditionDataProvider::testMatch_NotInOperator_True2()],
            [ProductClassIdConditionDataProvider::testMatch_NotInOperator_False1()],
            [ProductClassIdConditionDataProvider::testMatch_NotInOperator_False2()],
        ];
    }
}
