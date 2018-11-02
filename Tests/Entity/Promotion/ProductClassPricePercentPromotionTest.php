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
        list($promotionData, $object, $expectedData) = $this->$dataSet();
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
            ['dataProvider_testGetDiscount0'],
            ['dataProvider_testGetDiscount1'],
            ['dataProvider_testGetDiscount2']
        ];
    }

    protected function dataProvider_testGetDiscount0()
    {
        return [
            [
                'id' => 1,
                'value' => 100,
            ],
            new \stdClass(),
            [
                'id' => 1,
                'value' => 0,
            ],
        ];
    }

    protected function dataProvider_testGetDiscount1()
    {
        return [
            [
                'id' => 2,
                'value' => 200,
            ],
            new Cart(),
            [
                'id' => 2,
                'value' => 0,
            ],
        ];
    }

    protected function dataProvider_testGetDiscount2()
    {
        $promotionData = [
            'id' => 3,
            'value' => 50,
        ];
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02IncTax(50010);
        $expectedData = [
            'id' => 3,
            'value' => floor($ProductClass->getPrice02IncTax() * $promotionData['value'] / 100)
        ];

        return [
            $promotionData,
            $ProductClass,
            $expectedData,
        ];
    }

    protected function dataProvider_testGetDiscount3()
    {
        $promotionData = [
            'id' => 4,
            'value' => 15,
        ];
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02IncTax(8000);
        $expectedData = [
            'id' => 4,
            'value' => floor($ProductClass->getPrice02IncTax() * $promotionData['value'] / 100)
        ];

        return [
            $promotionData,
            $ProductClass,
            $expectedData,
        ];
    }
}
