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
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Tests\DataProvider\Entity\Condition\CartTotalConditionDataProvider;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends EccubeTestCase
{
    /**
     * @var CartTotalCondition
     */
    protected $cartTotalCondition;

    public function setUp()
    {
        parent::setUp();
        $this->cartTotalCondition = new CartTotalCondition();
        $this->cartTotalCondition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\GreaterThanOperator::TYPE,
            Operator\EqualOperator::TYPE,
            Operator\LessThanOperator::TYPE,
        ];
        $this->actual = $this->cartTotalCondition->getOperatorTypes();
        $this->verify();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($conditionData, $data, $expected) = $dataSet;

        $this->cartTotalCondition->setId($conditionData['id']);
        $this->cartTotalCondition->setValue($conditionData['value']);
        $this->cartTotalCondition->setOperator($conditionData['operator']);
        $result = $this->cartTotalCondition->match($data);
        $this->assertEquals($expected, $result);
    }

    public static function dataProvider_testMatch()
    {
        return [
            [CartTotalConditionDataProvider::testMatch_False1()],
            [CartTotalConditionDataProvider::testMatch_EqualOperator_True1()],
            [CartTotalConditionDataProvider::testMatch_EqualOperator_False1()],
            [CartTotalConditionDataProvider::testMatch_GreaterThanOperator_True1()],
            [CartTotalConditionDataProvider::testMatch_GreaterThanOperator_False1()],
            [CartTotalConditionDataProvider::testMatch_LessThanOperator_True1()],
            [CartTotalConditionDataProvider::testMatch_LessThanOperator_False1()],
        ];
    }
}
