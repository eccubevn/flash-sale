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
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
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

    public function setUp()
    {
        parent::setUp();
        $this->cartTotalPercentPromotion = new CartTotalPercentPromotion();
    }

    public function testGetDiscountItems_Invalid()
    {
        $this->expected = [];
        $this->actual = $this->cartTotalPercentPromotion->getDiscountItems(new \stdClass());
        $this->verify();
    }

    /**
     * @dataProvider getDiscountItemsDataProvider
     */
    public function testGetDiscountItems_ValidCart($total, $percent, $expected)
    {
        $Cart = new Cart();
        $Cart->setTotal($total);

        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $this->cartTotalPercentPromotion->setValue($percent);
        $this->cartTotalPercentPromotion->setEntityManager($this->entityManager);
        $result = $this->cartTotalPercentPromotion->getDiscountItems($Cart);


        self::assertEquals(0, $result[0]->getTax());
        self::assertEquals(0, $result[0]->getTaxRate());
        self::assertEquals(null, $result[0]->getTaxRuleId());
        self::assertEquals(null, $result[0]->getRoundingType());
        self::assertEquals(1, $result[0]->getQuantity());
        self::assertEquals($expected, $result[0]->getPrice());
        self::assertEquals(1, count($result));
        self::assertEquals($DiscountType, $result[0]->getOrderItemType());
        self::assertEquals($DiscountType->getName(), $result[0]->getProductName());
        self::assertEquals($Taxation, $result[0]->getTaxType());
        self::assertEquals($TaxInclude, $result[0]->getTaxDisplayType());
    }

    /**
     * @dataProvider getDiscountItemsDataProvider
     */
    public function testGetDiscountItems_ValidOrder($total, $percent, $expected)
    {
        $Cart = new Order();
        $Cart->setSubtotal($total);

        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $this->cartTotalPercentPromotion->setValue($percent);
        $this->cartTotalPercentPromotion->setEntityManager($this->entityManager);
        $result = $this->cartTotalPercentPromotion->getDiscountItems($Cart);


        self::assertEquals(0, $result[0]->getTax());
        self::assertEquals(0, $result[0]->getTaxRate());
        self::assertEquals(null, $result[0]->getTaxRuleId());
        self::assertEquals(null, $result[0]->getRoundingType());
        self::assertEquals(1, $result[0]->getQuantity());
        self::assertEquals($expected, $result[0]->getPrice());
        self::assertEquals(1, count($result));
        self::assertEquals($DiscountType, $result[0]->getOrderItemType());
        self::assertEquals($DiscountType->getName(), $result[0]->getProductName());
        self::assertEquals($Taxation, $result[0]->getTaxType());
        self::assertEquals($TaxInclude, $result[0]->getTaxDisplayType());
    }

    public function getDiscountItemsDataProvider()
    {
        return [
            [1000, 10, -100],
            [100, 50, -50],
        ];
    }
}
