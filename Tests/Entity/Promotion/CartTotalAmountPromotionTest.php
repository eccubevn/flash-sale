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

use Plugin\FlashSale\Entity\Discount;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Tests\DataProvider\Entity\Promotion\CartTotalAmountPromotionDataProvider;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class CartTotalAmountPromotionTest extends EccubeTestCase
{
    /**
     * @var CartTotalAmountPromotion
     */
    protected $cartTotalAmountPromotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartTotalAmountPromotion = new CartTotalAmountPromotion();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($dataSet)
    {
        list($promotionData, $object, $expectedData) = $dataSet;
        $this->cartTotalAmountPromotion->setId($promotionData['id']);
        $this->cartTotalAmountPromotion->setValue($promotionData['value']);

        $result = $this->cartTotalAmountPromotion->getDiscount($object);

        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
            [CartTotalAmountPromotionDataProvider::testGetDiscount_False1()],
            [CartTotalAmountPromotionDataProvider::testGetDiscount_Cart_True1()],
            [CartTotalAmountPromotionDataProvider::testGetDiscount_Order_True1()],
        ];
    }
}
