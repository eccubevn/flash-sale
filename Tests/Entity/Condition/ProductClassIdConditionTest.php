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

use Eccube\Entity\ProductClass;
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

    public function testMatch_Scenario0()
    {
        $actual = $this->productClassIdCondition->match(new \stdClass());
        $this->assertEquals(false, $actual);
    }

    /**
     * @param $conditionOperator
     * @param $conditionValue
     * @param $productClassId
     * @param $expected
     *
     * @dataProvider dataProvider_testMatch_Scenario1
     */
    public function testMatch_Scenario1($conditionOperator, $conditionValue, $productClassId, $expected)
    {
        $this->productClassIdCondition->setValue($conditionValue);
        $this->productClassIdCondition->setOperator($conditionOperator);

        $ProductClass = new ProductClass();
        $ProductClass->setPropertiesFromArray(['id' => $productClassId]);

        $actual = $this->productClassIdCondition->match($ProductClass);
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testMatch_Scenario1()
    {
        return [
            ['operator_in', '1,2,3,4,5', [5], true],
            ['operator_in', '1,2,3,4,5', [10], false],
            ['operator_in', '1,2,3,4,5', ['10'], false],
            ['operator_not_in', '1,2,3,4,5', [5], false],
            ['operator_not_in', '1,2,3,4,5', ['5'], false],
            ['operator_not_in', '1,2,3,4,5', [10], true],
            ['operator_not_in', '1,2,3,4,5', ['10', '7'], true],
        ];
    }
}
