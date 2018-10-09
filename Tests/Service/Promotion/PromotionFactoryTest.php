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

    public function testCreateFromArray_Invalid_Type()
    {
        $rules = $this->rulesData();
        $promotion = $rules['promotion'];
        $promotion['type'] = 'promotion_test_only';
        try {
            $data = PromotionFactory::createFromArray($promotion);
        } catch (\Exception $exception) {
            $data = 'promotion_test_only unsupported';
        }

        $this->expected = 'promotion_test_only unsupported';
        $this->actual = $data;
        $this->verify();
    }

    public function testCreateFromArray_Valid_1()
    {
        $rules = $this->rulesData();
        $rules['type'] = ProductClassPricePercentPromotion::TYPE;
        try {
            $data = PromotionFactory::createFromArray($rules);
        } catch (\Exception $exception) {
            $data = ProductClassPricePercentPromotion::TYPE.' unsupported';
        }

        $this->expected = true;
        $this->actual = is_object($data);
        $this->verify();
    }

    public function testCreateFromArray_Valid_2()
    {
        $rules = $this->rulesData();
        $rules['type'] = ProductClassPriceAmountPromotion::TYPE;
        try {
            $data = PromotionFactory::createFromArray($rules);
        } catch (\Exception $exception) {
            $data = ProductClassPriceAmountPromotion::TYPE.' unsupported';
        }

        $this->expected = true;
        $this->actual = is_object($data);
        $this->verify();
    }
}
