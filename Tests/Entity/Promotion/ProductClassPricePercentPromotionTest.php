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

use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;

class ProductClassPricePercentPromotionTest extends EccubeTestCase
{
    /**
     * @var ProductClassPricePercentPromotion
     */
    protected $productClassPricePercentPromotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->productClassPricePercentPromotion = new ProductClassPricePercentPromotion();
        $this->productClassPricePercentPromotion->setId(1);
    }

    public function testGetDiscount_Invalid_ProductClass()
    {
        $discount = $this->productClassPricePercentPromotion->getDiscount(new \stdClass());

        $this->assertEmpty($discount->getValue());
    }

    public function testGetDiscount()
    {
        $value = 10;
        $price02IncTax = 52498;
        $ProductClass = $this->getMockBuilder(ProductClass::class)->getMock();
        $ProductClass->method('getPrice02IncTax')->willReturn($price02IncTax);
        $this->productClassPricePercentPromotion->setValue($value);
        $discount = $this->productClassPricePercentPromotion->getDiscount($ProductClass);

        $this->assertEquals(floor($value*$price02IncTax/100), $discount->getValue());
    }
}
