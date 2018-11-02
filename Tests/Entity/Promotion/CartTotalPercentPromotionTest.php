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

namespace Plugin\FlashSale\Tests\Entity\Promotion;

use Eccube\Entity\Order;
use Eccube\Entity\Cart;
use Plugin\FlashSale\Entity\Discount;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class CartTotalPercentPromotionTest extends EccubeTestCase
{
    /**
     * @var CartTotalPercentPromotion
     */
    protected $cartTotalPercentPromotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartTotalPercentPromotion = new CartTotalPercentPromotion();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($dataSet)
    {
        list($promotionData, $object, $expectedData) = $this->$dataSet();

        $this->cartTotalPercentPromotion->setId($promotionData['id']);
        $this->cartTotalPercentPromotion->setValue($promotionData['value']);

        $result = $this->cartTotalPercentPromotion->getDiscount($object);

        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
            ['dataProvider_testGetDiscount1'],
            ['dataProvider_testGetDiscount2'],
            ['dataProvider_testGetDiscount3'],
        ];
    }

    protected function dataProvider_testGetDiscount1()
    {
        return [
            [
                'id' => 1,
                'value' => 100,
            ],
            new \stdClass(),
            [
                'id' => 1,
                'value' => 0,
            ],
        ];

    }
    protected function dataProvider_testGetDiscount2()
    {
        $promotionData = [
            'id' => 2,
            'value' => 10,
        ];
        $Cart = new Cart();
        $Cart->setTotal(1000);
        $expectedData = [
            'id' => 2,
            'value' => floor($Cart->getTotal() * $promotionData['value'] / 100)
        ];
        return [
            $promotionData,
            $Cart,
            $expectedData,
        ];
    }

    protected function dataProvider_testGetDiscount3()
    {
        $promotionData = [
            'id' => 3,
            'value' => 5,
        ];
        $Order = new Order();
        $Order->setSubtotal(4545);
        $expectedData = [
            'id' => 3,
            'value' => floor($Order->getSubtotal() * $promotionData['value'] / 100)
        ];
        return [
            $promotionData,
            $Order,
            $expectedData,
        ];
    }
}
