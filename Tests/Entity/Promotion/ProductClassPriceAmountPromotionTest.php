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

use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class ProductClassPriceAmountPromotionTest extends EccubeTestCase
{
    /**
     * @var ProductClassPriceAmountPromotion
     */
    protected $productClassPriceAmountPromotion;

    public function setUp()
    {
        parent::setUp();

        $this->productClassPriceAmountPromotion = new ProductClassPriceAmountPromotion();
    }

    public function testGetDiscountItems_Invalid()
    {
        $this->expected = [];
        $this->actual = $this->productClassPriceAmountPromotion->getDiscountItems(new \stdClass());
        $this->verify();
    }

    public function testGetDiscountItems_Valid()
    {
        $mProductClass = $this->getMockBuilder(ProductClass::class)->getMock();

        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $value = 100;
        $this->productClassPriceAmountPromotion->setValue($value);
        $this->productClassPriceAmountPromotion->setEntityManager($this->entityManager);

        $DiscountItems = $this->productClassPriceAmountPromotion->getDiscountItems($mProductClass);

        self::assertEquals(1, count($DiscountItems));
        self::assertEquals($value * -1, $DiscountItems[0]->getPrice());
        self::assertEquals(1, $DiscountItems[0]->getQuantity());
        self::assertEquals($DiscountType, $DiscountItems[0]->getOrderItemType());
        self::assertEquals($TaxInclude, $DiscountItems[0]->getTaxDisplayType());
        self::assertEquals($Taxation, $DiscountItems[0]->getTaxType());
        self::assertEquals(0, $DiscountItems[0]->getTax());
        self::assertEquals(0, $DiscountItems[0]->getTaxRate());
        self::assertEquals(null, $DiscountItems[0]->getTaxRuleId());
        self::assertEquals(null, $DiscountItems[0]->getRoundingType());
    }
}
