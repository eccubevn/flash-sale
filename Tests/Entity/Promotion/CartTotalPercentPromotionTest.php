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

use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Tests\Entity\PromotionTest;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class CartTotalPercentPromotionTest extends PromotionTest
{
    /**
     * @var CartTotalPercentPromotion
     */
    protected $promotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->promotion = new CartTotalPercentPromotion();
    }

    public static function dataProvider_testRawData_Scenario1()
    {
        return [
            [['id' => 1, 'type' => 'promotion_cart_percent_amount', 'value' => 10]],
        ];
    }

    public function testGetDiscount_Scenario0()
    {
        $this->promotion->setId(rand());
        $actual = $this->promotion->getDiscount(new \stdClass());
        $this->assertEquals(Discount::class, get_class($actual));
        $this->assertEquals($this->promotion->getId(), $actual->getPromotionId());
        $this->assertEquals(0, $actual->getValue());
    }

    /**
     * @param $promotionValue
     * @param $cartTotal
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario1
     */
    public function testGetDiscount_Scenario1($promotionValue, $cartTotal, $expectedValue)
    {
        $this->promotion->setId(rand());
        $this->promotion->setValue($promotionValue);

        $Cart = new Cart();
        $Cart->setTotal($cartTotal);

        $actual = $this->promotion->getDiscount($Cart);
        $this->assertEquals(get_class($actual), Discount::class);
        $this->assertEquals($this->promotion->getId(), $actual->getPromotionId());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    /**
     * @param $promotionValue
     * @param $orderSubTotal
     * @param $expectedValue
     * @dataProvider dataProvider_testGetDiscount_Scenario1
     */
    public function testGetDiscount_Scenario2($promotionValue, $orderSubTotal, $expectedValue)
    {
        $this->promotion->setId(rand());
        $this->promotion->setValue($promotionValue);

        $Order = new Order();
        $Order->setSubtotal($orderSubTotal);

        $actual = $this->promotion->getDiscount($Order);
        $this->assertEquals(get_class($actual), Discount::class);
        $this->assertEquals($this->promotion->getId(), $actual->getPromotionId());
        $this->assertEquals($expectedValue, $actual->getValue());
    }

    public static function dataProvider_testGetDiscount_Scenario1($testMethod = null, $orderSubtotal = 12345)
    {
        return [
            [10, $orderSubtotal, floor(10*$orderSubtotal/100)],
            [51, $orderSubtotal, floor(51*$orderSubtotal/100)],
        ];
    }
}
