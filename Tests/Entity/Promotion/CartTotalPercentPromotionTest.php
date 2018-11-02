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
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Tests\DataProvider\Entity\Promotion\CartTotalPercentPromotionDataProvider;

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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->cartTotalPercentPromotion = new CartTotalPercentPromotion();
    }

    /**
     * @param $dataSet
     * @dataProvider dataProvider_testGetDiscount
     */
    public function testGetDiscount($dataSet)
    {
        list($promotionData, $object, $expectedData) = $dataSet;
        $this->cartTotalPercentPromotion->setId($promotionData['id']);
        $this->cartTotalPercentPromotion->setValue($promotionData['value']);

        $result = $this->cartTotalPercentPromotion->getDiscount($object);
        $this->assertEquals(get_class($result), Discount::class);
        $this->assertEquals($result->getPromotionId(), $expectedData['id']);
        $this->assertEquals($result->getValue(), $expectedData['value']);
    }

    public function dataProvider_testGetDiscount()
    {
        return [
//            [CartTotalPercentPromotionDataProvider::testGetDiscount_False1()],
//            [CartTotalPercentPromotionDataProvider::testGetDiscount_Cart_True1()],
            [CartTotalPercentPromotionDataProvider::testGetDiscount_Order_True1()],
        ];
    }
}
