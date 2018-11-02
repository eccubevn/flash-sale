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
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Tests\DataProvider\Entity\Promotion\ProductClassPricePercentPromotionDataProvider;

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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->productClassPricePercentPromotion = new ProductClassPricePercentPromotion();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($dataSet)
    {
        list($promotionData, $object, $expectedData) = $dataSet;
        $this->productClassPricePercentPromotion->setId($promotionData['id']);
        $this->productClassPricePercentPromotion->setValue($promotionData['value']);

        $result = $this->productClassPricePercentPromotion->getDiscount($object);
        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
            [ProductClassPricePercentPromotionDataProvider::testGetDiscount_True1()],
            [ProductClassPricePercentPromotionDataProvider::testGetDiscount_False1()],
            [ProductClassPricePercentPromotionDataProvider::testGetDiscount_False2()],
        ];
    }
}
