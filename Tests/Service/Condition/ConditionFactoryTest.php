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

        $this->expected = true;
        $this->actual = is_object($data);
        $this->verify();

        $condition[0]['type'] = 'condition_product_class_id_test_null';
        try {
            $data = ConditionFactory::createFromArray($condition[0]);
        } catch (\Exception $exception) {
            $data = 'condition_product_class_id_test_null unsupported';
        }

        $this->expected = 'condition_product_class_id_test_null unsupported';
        $this->actual = $data;
        $this->verify();

        unset($condition[0]['type']);
        try {
            $data = ConditionFactory::createFromArray($condition[0]);
        } catch (\Exception $exception) {
            $data = '$data[type] must be required';
        }

        $this->expected = '$data[type] must be required';
        $this->actual = $data;
        $this->verify();
    }
}
