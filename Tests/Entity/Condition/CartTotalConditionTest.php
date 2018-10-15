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
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Service\Operator\GreaterThanOperator;
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Service\Operator\LessThanOperator;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class CartTotalConditionTest extends AbstractEntityTest
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

    public function testMatch_Invalid()
    {
        $CartTotalCondition = new CartTotalCondition();
        $data = $CartTotalCondition->match(new \stdClass());
        self::assertFalse($data);
    }

    public function testMatch_InOperator_InValid_Cart()
    {
        $CartTotalCondition = new CartTotalCondition();
        $CartTotalCondition->setOperator(GreaterThanOperator::TYPE);
        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
        $CartTotalCondition->setOperatorFactory(new OperatorFactory());

        $cart = new Cart();
        $item = new CartItem();
        $item->setProductClass($this->ProductClass1);
        $cart->addItem($item);
        $cart->setTotal(10);
        $data = $CartTotalCondition->match($cart);

        self::assertFalse($data);
    }

    public function testMatch_InOperator_Valid_Order()
    {
        $CartTotalCondition = new CartTotalCondition();
        $CartTotalCondition->setOperator(LessThanOperator::TYPE);
        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
        $CartTotalCondition->setOperatorFactory(new OperatorFactory());

        $order = new Order();
        $item = new OrderItem();
        $item->setProductClass($this->ProductClass1);
        $order->addItem($item);
        $order->setTotal(100000);
        $data = $CartTotalCondition->match($order);

        self::assertTrue($data);
    }

    public function testMatch_InOperator_InValid_Order()
    {
        $CartTotalCondition = new CartTotalCondition();
        $CartTotalCondition->setOperator(GreaterThanOperator::TYPE);
        $CartTotalCondition->setValue($this->ProductClass1->getPrice02IncTax());
        $CartTotalCondition->setOperatorFactory(new OperatorFactory());

        $order = new Order();
        $item = new OrderItem();
        $item->setProductClass($this->ProductClass1);
        $order->addItem($item);
        $order->setTotal(10);
        $data = $CartTotalCondition->match($order);

        self::assertFalse($data);
    }
}
