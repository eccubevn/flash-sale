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

use Eccube\Tests\EccubeTestCase;
use Eccube\Entity\ProductClass;
use Eccube\Entity\Cart;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Tests\DataProvider\Entity\Promotion\ProductClassPriceAmountPromotionDataProvider;

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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->productClassPriceAmountPromotion = new ProductClassPriceAmountPromotion();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($dataSet)
    {
        list($promotionData, $object, $expectedData) = $dataSet;
        $this->productClassPriceAmountPromotion->setId($promotionData['id']);
        $this->productClassPriceAmountPromotion->setValue($promotionData['value']);

        $result = $this->productClassPriceAmountPromotion->getDiscount($object);

        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
            [ProductClassPriceAmountPromotionDataProvider::testGetDiscount_True1()],
            [ProductClassPriceAmountPromotionDataProvider::testGetDiscount_False1()],
            [ProductClassPriceAmountPromotionDataProvider::testGetDiscount_False2()],
        ];
    }
}
