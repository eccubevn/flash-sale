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

use Eccube\Entity\Order;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Service\Operator\AllOperator;
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassRuleTest
 * @package Plugin\FlashSale\Tests\Entity\Rule
 */
class CartRuleTest extends AbstractEntityTest
{
    /** @var Product */
    protected $Product;

    /** @var ProductClass */
    protected $ProductClass1;

    public function setUp()
    {
        parent::setUp();

        $this->Product = $this->createProduct('テスト商品', 3);
        $this->ProductClass1 = $this->Product->getProductClasses()[0];
    }

    public function testConstructor()
    {
        $productClassRule = new CartRule();
        $productClassRule->setId(1);
        $productClassRule = $productClassRule->toArray();

        $this->expected = 1;
        $this->actual = $productClassRule['id'];
        $this->verify();

        $this->expected = null;

        $this->actual = $productClassRule['operator'];
        $this->verify();

        $this->actual = $productClassRule['FlashSale'];
        $this->verify();

        $this->actual = $productClassRule['Promotion'];
        $this->verify();

        $this->actual = $productClassRule['discriminator'];
        $this->verify();

        $this->actual = $productClassRule['operatorFactory'];
        $this->verify();

        $this->actual = $productClassRule['discriminatorManager'];
        $this->verify();
    }

    public function testAddConditions()
    {
        $condition = new CartTotalCondition();
        $condition->setId(100);
        $condition->setValue(5);
        $CartRule = new CartRule();
        $CartRule->addConditions($condition);

        self::assertEquals($condition->getId(), $CartRule->getConditions()->get(0)->getId());
    }


    public function testRemoveConditions()
    {
        $condition = new CartTotalCondition();
        $condition->setId(100);
        $condition->setValue(5);
        $productClassRule = new CartRule();
        $productClassRule->addConditions($condition);
        self::assertEquals($condition->getId(), $productClassRule->getConditions()->get(0)->getId());

        $productClassRule->removeCondition($condition);
        self::assertEquals([], $productClassRule->getConditions()->getKeys());
    }

    public function testDiscriminatorManager_AllOperator()
    {
        $operatorFactory = new OperatorFactory();
        $operatorFactory->createByType(AllOperator::TYPE);

        $discriminatorManager = new DiscriminatorManager();

        $CartRule = new CartRule();
        $CartRule->setOperatorFactory($operatorFactory);
        $CartRule->setDiscriminatorManager($discriminatorManager);

        self::assertEquals(CartRule::TYPE, $CartRule->getDiscriminator()->getType());
    }

    public function testDiscriminatorManager_InOperator()
    {
        $operatorFactory = new OperatorFactory();
        $operatorFactory->createByType(InOperator::TYPE);

        $discriminatorManager = new DiscriminatorManager();

        $CartRule = new CartRule();
        $CartRule->setOperatorFactory($operatorFactory);
        $CartRule->setDiscriminatorManager($discriminatorManager);

        self::assertEquals(CartRule::TYPE, $CartRule->getDiscriminator()->getType());
    }

    public function testGetOperatorTypes()
    {
        $CartRule = new CartRule();
        $data = $CartRule->getOperatorTypes();

        self::assertContains(InOperator::TYPE, $data);
        self::assertContains(AllOperator::TYPE, $data);
    }

    public function testGetConditionTypes()
    {
        $CartRule = new CartRule();
        $data = $CartRule->getConditionTypes();

        self::assertContains(CartTotalCondition::TYPE, $data);
    }

    public function testGetPromotionTypes()
    {
        $CartRule = new CartRule();
        $data = $CartRule->getPromotionTypes();

        self::assertContains(CartTotalPercentPromotion::TYPE, $data);
        self::assertContains(CartTotalAmountPromotion::TYPE, $data);
    }

    public function testGetFlashSale()
    {
        $FlashSale = new FlashSale();
        $CartRule = new CartRule();
        $CartRule->setFlashSale($FlashSale);

        self::assertEquals($FlashSale, $CartRule->getFlashSale());
    }

    public function testMatch_Invalid_Order()
    {
        $CartRule = new CartRule();
        self::assertFalse($CartRule->match(new \stdClass()));
    }

    public function testGetDiscountItems_Not_instanceof()
    {
        $CartRule = new CartRule();
        self::assertEquals([], $CartRule->getDiscountItems(new \stdClass()));
    }
}
