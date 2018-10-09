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
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Service\Operator\AllOperator;
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Service\Operator\OperatorFactory;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class ProductClassPricePercentPromotionTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testGetDiscountItems()
    {

    }
}
