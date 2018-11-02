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

namespace Plugin\FlashSale\Tests\Entity\Condition;

use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Service\Operator\InOperator;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\Entity\AbstractEntityTest;

/**
 * Class ProductClassIdConditionTest
 * @package Plugin\FlashSale\Tests\Entity\Condition
 */
class ProductClassIdConditionTest extends AbstractEntityTest
{
    /** @var Product */
    protected $Product;

    /** @var ProductClass */
    protected $ProductClass1;

    public function setUp()
    {
        $this->markTestSkipped();
        parent::setUp();

        $this->Product = $this->createProduct('テスト商品', 3);
        $this->ProductClass1 = $this->Product->getProductClasses()[0];
    }

    public function testMatch_Invalid()
    {
        $productClassRule = new ProductClassIdCondition();
        $data = $productClassRule->match(new \stdClass());
        self::assertFalse($data);
    }

    public function testMatch_InOperator_Valid()
    {
        $productClassRule = new ProductClassIdCondition();
        $productClassRule->setOperator(InOperator::TYPE);
        $productClassRule->setValue($this->ProductClass1->getId());
        $productClassRule->setOperatorFactory(new OperatorFactory());
        $data = $productClassRule->match($this->ProductClass1);

        self::assertTrue($data);
    }

    public function testMatch_InOperator_Invalid()
    {
        $productClassRule = new ProductClassIdCondition();
        $productClassRule->setOperator(InOperator::TYPE);
        $productClassRule->setValue(0);
        $productClassRule->setOperatorFactory(new OperatorFactory());
        $data = $productClassRule->match($this->ProductClass1);

        self::assertFalse($data);
    }
}
