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

    public function testCreateFromArray()
    {
        $rules = $this->rulesData();
        $promotion = $rules['promotion'];
        $data = PromotionFactory::createFromArray($promotion);

        $this->expected = true;
        $this->actual = is_object($data);
        $this->verify();

        $promotion['type'] = 'promotion_test_only';
        try {
            $data = PromotionFactory::createFromArray($promotion);
        } catch (\Exception $exception) {
            $data = 'promotion_test_only unsupported';
        }

        $this->expected = 'promotion_test_only unsupported';
        $this->actual = $data;
        $this->verify();

        unset($promotion['type']);
        try {
            $data = PromotionFactory::createFromArray($promotion);
        } catch (\Exception $exception) {
            $data = '$data[type] must be required';
        }

        $this->expected = '$data[type] must be required';
        $this->actual = $data;
        $this->verify();
    }
}
