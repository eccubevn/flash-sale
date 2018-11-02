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

use Eccube\Entity\Cart;
use Eccube\Entity\CartItem;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends AbstractEntityTest
{
    use CartTotalConditionDataProviderTrait;

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
        list($conditionData, $data, $expected) = $this->$dataSet();

        $this->cartTotalCondition->setId($conditionData['id']);
        $this->cartTotalCondition->setValue($conditionData['value']);
        $this->cartTotalCondition->setOperator($conditionData['operator']);
        $result = $this->cartTotalCondition->match($data);
        $this->assertEquals($expected, $result);
    }

    public static function dataProvider_testMatch()
    {
        return [
            'true#1' => ['dataProvider_testMatch0'],
            'true#2' => ['dataProvider_testMatch1'],
            'true#3' => ['dataProvider_testMatch2'],
            'true#4' => ['dataProvider_testMatch3'],
            'true#5' => ['dataProvider_testMatch4'],
            'true#6' => ['dataProvider_testMatch5'],
            'true#7' => ['dataProvider_testMatch6'],
        ];
    }
}
