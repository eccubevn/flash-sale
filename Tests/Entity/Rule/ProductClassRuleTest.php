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

namespace Plugin\FlashSale\Tests\Entity;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\FlashSale;
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
class ProductClassRuleTest extends AbstractEntityTest
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testConstructor()
    {
        $productClassRule = new ProductClassRule();
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
        $condition = new ProductClassIdCondition();
        $condition->setId(100);
        $condition->setValue(5);
        $productClassRule = new ProductClassRule();
        $productClassRule->addConditions($condition);

        self::assertEquals($condition->getId(), $productClassRule->getConditions()->get(0)->getId());
    }


    public function testRemoveConditions()
    {
        $condition = new ProductClassIdCondition();
        $condition->setId(100);
        $condition->setValue(5);
        $productClassRule = new ProductClassRule();
        $productClassRule->addConditions($condition);
        self::assertEquals($condition->getId(), $productClassRule->getConditions()->get(0)->getId());

        $productClassRule->removeCondition($condition);
        self::assertEquals([], $productClassRule->getConditions()->getKeys());
    }

    public function testDiscriminatorManager()
    {
        $operatorFactory = new OperatorFactory();
        $operatorFactory->createByType(AllOperator::TYPE);

        $discriminatorManager = new DiscriminatorManager();

        $productClassRule = new ProductClassRule();
        $productClassRule->setOperatorFactory($operatorFactory);
        $productClassRule->setDiscriminatorManager($discriminatorManager);

        $productClassRule->toArray();

        $this->expected = ProductClassRule::TYPE;
        $this->actual = $productClassRule['discriminator']->getType();

        $this->verify();
    }

    public function testGetOperatorTypes()
    {
        $productClassRule = new ProductClassRule();
        $data = $productClassRule->getOperatorTypes();

        self::assertArraySubset([InOperator::TYPE, AllOperator::TYPE], $data);
    }

    public function testGetConditionTypes()
    {
        $productClassRule = new ProductClassRule();
        $data = $productClassRule->getConditionTypes();

        self::assertArraySubset([ProductClassIdCondition::TYPE], $data);
    }

    public function testGetPromotionTypes()
    {
        $productClassRule = new ProductClassRule();
        $data = $productClassRule->getPromotionTypes();

        self::assertArraySubset([ProductClassPricePercentPromotion::TYPE], $data);
    }

    public function testGetFlashSale()
    {
        $FlashSale = new FlashSale();
        $productClassRule = new ProductClassRule();
        $productClassRule->setFlashSale($FlashSale);

        self::assertEquals($FlashSale, $productClassRule->getFlashSale());
    }
}
