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

use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
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

    public function testGetDiscountItems()
    {
        $ProductClassPricePercentPromotion = new ProductClassPricePercentPromotion();
        $ProductClassPricePercentPromotion->setEntityManager($this->entityManager);
        $ProductClassPricePercentPromotion->setValue(5);

        $OrderItem = $ProductClassPricePercentPromotion->getDiscountItems($this->ProductClass1);
        dump([
            $OrderItem[0]
        ]);

        $price = -1 * ($this->ProductClass1->getPrice02() / 100 * $ProductClassPricePercentPromotion->getValue());

        self::assertEquals($price, $OrderItem[0]->getPrice());
        self::assertEquals($price, $OrderItem[0]->setQuantity());
    }
}
