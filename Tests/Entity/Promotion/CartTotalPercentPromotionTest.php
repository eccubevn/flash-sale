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
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;

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
        $this->cartTotalPercentPromotion->setId(1);
    }

    public function testGetDiscount_Invalid_Not_Cart_Order()
    {
        $discount = $this->cartTotalPercentPromotion->getDiscount(new \stdClass());
        $this->assertEmpty($discount->getValue());
    }

    public function testGetDiscount_Valid_Cart()
    {
        $value = 25;
        $total = 100000;
        $this->cartTotalPercentPromotion->setValue($value);

        $cart = new Cart();
        $cart->setTotal($total);

        $discount = $this->cartTotalPercentPromotion->getDiscount($cart);
        $this->assertEquals(floor($total*$value/100), $discount->getValue());
    }

    public function testGetDiscount_Valid_Order()
    {
        $value = 20;
        $total = 50000;
        $this->cartTotalPercentPromotion->setValue($value);

        $cart = new Order();
        $cart->setSubtotal($total);

        $discount = $this->cartTotalPercentPromotion->getDiscount($cart);
        $this->assertEquals(floor($total*$value/100), $discount->getValue());
    }
}
