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

namespace Plugin\FlashSale\Tests\Entity\Rule;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Promotion as Promotion;


/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class CartRuleTest extends EccubeTestCase
{
    /**
     * @var CartRule
     */
    protected $cartRule;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->cartRule = new CartRule();
    }

    public function testGetOperatorTypes()
    {
        $this->expected = [
            Operator\OrOperator::TYPE,
            Operator\AllOperator::TYPE,
        ];
        $this->actual = $this->cartRule->getOperatorTypes();
        $this->verify();
    }

    public function testGetConditionTypes()
    {
        $this->expected = [
            Condition\CartTotalCondition::TYPE,
        ];
        $this->actual = $this->cartRule->getConditionTypes();
        $this->verify();

    }

    public function testGetPromotionTypes()
    {
        $this->expected = [
            Promotion\CartTotalPercentPromotion::TYPE,
            Promotion\CartTotalAmountPromotion::TYPE,
        ];
        $this->actual = $this->cartRule->getPromotionTypes();
        $this->verify();
    }

//    public function testGetFlashSale()
//    {
//        $FlashSale = new FlashSale();
//        $CartRule = new CartRule();
//        $CartRule->setFlashSale($FlashSale);
//
//        self::assertEquals($FlashSale, $CartRule->getFlashSale());
//    }
//
//    public function testMatch_Invalid_Order()
//    {
//        $CartRule = new CartRule();
//        self::assertFalse($CartRule->match(new \stdClass()));
//    }
//
//    public function testGetDiscountItems_Not_instanceof()
//    {
//        $CartRule = new CartRule();
//        self::assertEquals([], $CartRule->getDiscountItems(new \stdClass()));
//    }
}
