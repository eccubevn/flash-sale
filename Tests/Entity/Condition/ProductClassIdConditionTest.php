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
}
