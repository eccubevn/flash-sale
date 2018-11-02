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
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\DataProvider\Entity\Condition\ProductCategoryIdConditionDataProvider;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductCategoryIdConditionTest extends EccubeTestCase
{
    /**
     * @var ProductCategoryIdCondition
     */
    protected $productCategoryIdCondition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->productCategoryIdCondition = new ProductCategoryIdCondition();
        $this->productCategoryIdCondition->setOperatorFactory($this->container->get(OperatorFactory::class));
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\InOperator::TYPE,
            Operator\NotInOperator::TYPE,
        ];
        $this->actual = $this->productCategoryIdCondition->getOperatorTypes();
        $this->verify();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testMatch
     */
    public function testMatch($dataSet)
    {
        list($conditionData, $data, $expected) = $dataSet;

        $this->productCategoryIdCondition->setId($conditionData['id']);
        $this->productCategoryIdCondition->setValue($conditionData['value']);
        $this->productCategoryIdCondition->setOperator($conditionData['operator']);
        $result = $this->productCategoryIdCondition->match($data);
        $this->assertEquals($expected, $result);
    }

    public static function dataProvider_testMatch()
    {
        return [
            [ProductCategoryIdConditionDataProvider::testMatch_False1()],
            [ProductCategoryIdConditionDataProvider::testMatch_InOperator_True1()],
            [ProductCategoryIdConditionDataProvider::testMatch_InOperator_True2()],
            [ProductCategoryIdConditionDataProvider::testMatch_InOperator_False1()],
            [ProductCategoryIdConditionDataProvider::testMatch_InOperator_False2()],
            [ProductCategoryIdConditionDataProvider::testMatch_NotInOperator_True1()],
            [ProductCategoryIdConditionDataProvider::testMatch_NotInOperator_True2()],
            [ProductCategoryIdConditionDataProvider::testMatch_NotInOperator_False1()],
            [ProductCategoryIdConditionDataProvider::testMatch_NotInOperator_False2()],
        ];
    }
}
