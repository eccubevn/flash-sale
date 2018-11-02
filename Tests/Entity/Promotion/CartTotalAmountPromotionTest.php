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
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class CartTotalAmountPromotionTest extends EccubeTestCase
{
    /**
     * @var CartTotalAmountPromotion
     */
    protected $cartTotalAmountPromotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartTotalAmountPromotion = new CartTotalAmountPromotion();
    }

    /**
     * @param $promotionData
     * @param $object
     * @param $expectedData
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($promotionData, $object, $expectedData)
    {
        $this->cartTotalAmountPromotion->setId($promotionData['id']);
        $this->cartTotalAmountPromotion->setValue($promotionData['value']);

        $result = $this->cartTotalAmountPromotion->getDiscount($object);

        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
            [
                [
                    'id' => 1,
                    'value' => 100,
                ],
                new \stdClass(),
                [
                    'id' => 1,
                    'value' => 0,
                ],
            ],
            [
                [
                    'id' => 2,
                    'value' => 200,
                ],
                new Cart(),
                [
                    'id' => 2,
                    'value' => 200,
                ],
            ],
            [
                [
                    'id' => 3,
                    'value' => 300,
                ],
                new Order(),
                [
                    'id' => 3,
                    'value' => 300,
                ],
            ]
        ];
    }
}
