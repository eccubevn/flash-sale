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

namespace Plugin\FlashSale\Service\Rule;

use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class RuleFactoryTest extends AbstractServiceTestCase
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
        $data = RuleFactory::createFromArray($rules);

        $this->expected = true;
        $this->actual = is_object($data);
        $this->verify();

        $rules['type'] = 'rule_test_only';
        try {
            $data = RuleFactory::createFromArray($rules);
        } catch (\Exception $exception) {
            $data = 'rule_test_only unsupported';
        }

        $this->expected = 'rule_test_only unsupported';
        $this->actual = $data;
        $this->verify();

        unset($rules['type']);
        try {
            $data = RuleFactory::createFromArray($rules);
        } catch (\Exception $exception) {
            $data = '$data[type] must be required';
        }

        $this->expected = '$data[type] must be required';
        $this->actual = $data;
        $this->verify();
    }
}
