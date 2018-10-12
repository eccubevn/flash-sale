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
use Eccube\Entity\CartItem;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class CartTotalPercentPromotionTest extends EccubeTestCase
{
    /** @var Product */
    protected $Product;

    /** @var ProductClass */
    protected $ProductClass1;

    public function setUp()
    {
        parent::setUp();

        $this->Product = $this->createProduct('テスト商品', 3);
        $this->ProductClass1 = $this->Product->getProductClasses()[0];
    }

    public function testGetDiscountItems_Invalid_Not_Cart_Order()
    {
        $CartTotalPercentPromotion = new CartTotalPercentPromotion();
        $CartTotalPercentPromotion->setEntityManager($this->entityManager);
        $CartTotalPercentPromotion->setValue(150);

        $OrderItem = $CartTotalPercentPromotion->getDiscountItems(new \stdClass());

        self::assertEmpty($OrderItem);
    }

    public function testGetDiscountItems_Invalid_Cart()
    {
        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $CartTotalPercentPromotion = new CartTotalPercentPromotion();
        $CartTotalPercentPromotion->setEntityManager($this->entityManager);
        $CartTotalPercentPromotion->setValue(150);

        $cart = new Cart();
        $item = new CartItem();
        $item->setProductClass($this->ProductClass1);
        $cart->addItem($item);
        $cart->setTotal(100000);

        $OrderItem = $CartTotalPercentPromotion->getDiscountItems($cart);

        $price = -1 * floor($cart->getTotal() / 100 * $CartTotalPercentPromotion->getValue());

        self::assertEquals($price, $OrderItem[0]->getPrice());
        self::assertEquals(1, $OrderItem[0]->getQuantity());
        self::assertEquals($DiscountType, $OrderItem[0]->getOrderItemType());
        self::assertEquals($TaxInclude, $OrderItem[0]->getTaxDisplayType());
        self::assertEquals($Taxation, $OrderItem[0]->getTaxType());
    }

    public function testGetDiscountItems_Invalid_Order()
    {
        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $CartTotalPercentPromotion = new CartTotalPercentPromotion();
        $CartTotalPercentPromotion->setEntityManager($this->entityManager);
        $CartTotalPercentPromotion->setValue(150);

        $order = new Order();
        $item = new OrderItem();
        $item->setProductClass($this->ProductClass1);
        $order->addItem($item);
        $order->setTotal(10);

        $OrderItem = $CartTotalPercentPromotion->getDiscountItems($order);

        $price = -1 * floor($order->getSubtotal() / 100 * $CartTotalPercentPromotion->getValue());

        self::assertEquals($price, $OrderItem[0]->getPrice());
        self::assertEquals(1, $OrderItem[0]->getQuantity());
        self::assertEquals($DiscountType, $OrderItem[0]->getOrderItemType());
        self::assertEquals($TaxInclude, $OrderItem[0]->getTaxDisplayType());
        self::assertEquals($Taxation, $OrderItem[0]->getTaxType());
    }
}
