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
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Entity\Discount;


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
        $this->cartTotalAmountPromotion->setId(1);
    }

    public function testGetDiscount_Invalid_Not_Cart_Order()
    {
        $discount = $this->cartTotalAmountPromotion->getDiscount(new \stdClass());
        $this->assertEmpty($discount->getValue());
    }

    public function testGetDiscount_Valid_Cart()
    {
        $value = 160;
        $this->cartTotalAmountPromotion->setValue($value);
        $mCart = $this->getMockBuilder(Cart::class)->getMock();
        $discount = $this->cartTotalAmountPromotion->getDiscount($mCart);

        $this->assertEquals($value, $discount->getValue());
    }

    public function testGetDiscount_Valid_Order()
    {
        $value = 5000;
        $this->cartTotalAmountPromotion->setValue($value);
        $mCart = $this->getMockBuilder(Order::class)->disableOriginalConstructor()->getMock();
        $discount = $this->cartTotalAmountPromotion->getDiscount($mCart);

        $this->assertEquals($value, $discount->getValue());
    }
}
