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
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class ProductClassPricePercentPromotionTest extends EccubeTestCase
{
    /**
     * @var ProductClassPricePercentPromotion
     */
    protected $productClassPricePercentPromotion;

    public function setUp()
    {
        parent::setUp();
        $this->productClassPricePercentPromotion = new ProductClassPricePercentPromotion();
    }

    public function testGetDiscountItems_Invalid()
    {
        $this->expected = [];
        $this->actual = $this->productClassPricePercentPromotion->getDiscountItems(new \stdClass());
        $this->verify();
    }

    /**
     * @param $price
     * @param $percent
     * @param $expect
     * @dataProvider getDiscountItemsDataProvider
     */
    public function testGetDiscountItems_Valid($price, $percent, $expect)
    {
        $mProductClass = $this->getMockBuilder(ProductClass::class)->getMock();
        $mProductClass->method('getPrice02IncTax')
            ->willReturn($price);

        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $this->productClassPricePercentPromotion->setValue($percent);
        $this->productClassPricePercentPromotion->setEntityManager($this->entityManager);

        $DiscountItems = $this->productClassPricePercentPromotion->getDiscountItems($mProductClass);

        self::assertEquals(1, count($DiscountItems));
        self::assertEquals($expect, $DiscountItems[0]->getPrice());
        self::assertEquals(1, $DiscountItems[0]->getQuantity());
        self::assertEquals($DiscountType, $DiscountItems[0]->getOrderItemType());
        self::assertEquals($TaxInclude, $DiscountItems[0]->getTaxDisplayType());
        self::assertEquals($Taxation, $DiscountItems[0]->getTaxType());
        self::assertEquals(0, $DiscountItems[0]->getTax());
        self::assertEquals(0, $DiscountItems[0]->getTaxRate());
        self::assertEquals(null, $DiscountItems[0]->getTaxRuleId());
        self::assertEquals(null, $DiscountItems[0]->getRoundingType());
    }

    public function getDiscountItemsDataProvider()
    {
        return [
            [500, 5, -25],
            [1025, 3, -30],
        ];
    }
}
