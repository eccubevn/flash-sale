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
use Eccube\Entity\Product;
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

    public function testGetDiscountItems_Invalid_ProductClass()
    {
        $ProductClassPricePercentPromotion = new ProductClassPricePercentPromotion();
        $ProductClassPricePercentPromotion->setEntityManager($this->entityManager);
        $ProductClassPricePercentPromotion->setValue(5);

        $OrderItem = $ProductClassPricePercentPromotion->getDiscountItems(new \stdClass());

        self::assertEmpty($OrderItem);
    }

    public function testGetDiscountItems()
    {
        $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

        $ProductClassPricePercentPromotion = new ProductClassPricePercentPromotion();
        $ProductClassPricePercentPromotion->setEntityManager($this->entityManager);
        $ProductClassPricePercentPromotion->setValue(5);

        $OrderItem = $ProductClassPricePercentPromotion->getDiscountItems($this->ProductClass1);

        $price = -1 * ($this->ProductClass1->getPrice02() / 100 * $ProductClassPricePercentPromotion->getValue());

        self::assertEquals($price, $OrderItem[0]->getPrice());
        self::assertEquals(1, $OrderItem[0]->getQuantity());
        self::assertEquals($DiscountType, $OrderItem[0]->getOrderItemType());
        self::assertEquals($TaxInclude, $OrderItem[0]->getTaxDisplayType());
        self::assertEquals($Taxation, $OrderItem[0]->getTaxType());
    }
}
