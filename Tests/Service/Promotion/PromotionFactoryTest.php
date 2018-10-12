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

namespace Plugin\FlashSale\Service\Promotion;

use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class PromotionFactoryTest extends AbstractServiceTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
    }

    public function testCreateFromArray_Invalid_Type_Not_Isset()
    {
        $rules = $this->rulesData();
        $promotion = $rules['promotion'];
        unset($promotion['type']);
        try {
            PromotionFactory::createFromArray($promotion);
        }catch (\Exception $exception){
            $this->assertEquals($exception->getMessage(), '$data[type] must be required');
        }
    }

    public function testCreateFromArray_Invalid_Type()
    {
        $rules = $this->rulesData();
        $promotion = $rules['promotion'];
        $promotion['type'] = 'promotion_test_only';
        try {
            PromotionFactory::createFromArray($promotion);
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getMessage(), 'promotion_test_only unsupported');
        }
    }

    public function testCreateFromArray_Valid_1()
    {
        $rules = $this->rulesData();
        $rules['type'] = ProductClassPricePercentPromotion::TYPE;
        $data = PromotionFactory::createFromArray($rules);

        self::assertInstanceOf(ProductClassPricePercentPromotion::class, $data);
    }

    public function testCreateFromArray_Valid_2()
    {
        $rules = $this->rulesData();
        $rules['type'] = ProductClassPriceAmountPromotion::TYPE;
        $data = PromotionFactory::createFromArray($rules);

        self::assertInstanceOf(ProductClassPriceAmountPromotion::class, $data);
    }

    public function testCreateFromArray_Valid_33()
    {
        $rules = $this->rulesData();
        $rules['type'] = ProductClassPriceAmountPromotion::TYPE;
        $data = PromotionFactory::createFromArray($rules);

        self::assertInstanceOf(ProductClassPriceAmountPromotion::class, $data);
    }
}
