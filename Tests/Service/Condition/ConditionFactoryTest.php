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

namespace Plugin\FlashSale\Tests\Service\Condition;

use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Service\Condition\ConditionFactory;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class ConditionFactoryTest extends AbstractServiceTestCase
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
        $condition = $rules['conditions'];

        $data = ConditionFactory::createFromArray($condition[0]);
        self::assertInstanceOf(ProductClassIdCondition::class, $data);

        $condition[0]['type'] = ProductCategoryIdCondition::TYPE;
        $data = ConditionFactory::createFromArray($condition[0]);
        self::assertInstanceOf(ProductCategoryIdCondition::class, $data);

        $condition[0]['type'] = CartTotalCondition::TYPE;
        $data = ConditionFactory::createFromArray($condition[0]);
        self::assertInstanceOf(CartTotalCondition::class, $data);

        $condition[0]['type'] = 'condition_product_class_id_test_null';
        try {
            $data = ConditionFactory::createFromArray($condition[0]);
        } catch (\Exception $exception) {
            $data = 'condition_product_class_id_test_null unsupported';
        }

        $this->expected = 'condition_product_class_id_test_null unsupported';
        self::assertEquals($this->expected, $data);

        unset($condition[0]['type']);
        try {
            $data = ConditionFactory::createFromArray($condition[0]);
        } catch (\Exception $exception) {
            $data = '$data[type] must be required';
        }

        $this->expected = '$data[type] must be required';
        self::assertEquals($this->expected, $data);
    }
}
