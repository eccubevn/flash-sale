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

namespace Plugin\FlashSale\Tests\Service;

use Plugin\FlashSale\Service\FlashSaleService;

class FlashSaleServiceTest extends AbstractServiceTestCase
{
    /** @var FlashSaleService */
    protected $flashSaleService;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleService = $this->container->get(FlashSaleService::class);
    }

    public function testGetMetadata_rule_types()
    {
        $data = $this->flashSaleService->getMetadata();

        $this->expected = true;
        $this->actual = array_key_exists('rule_types', $data);
        $this->verify();
    }

    public function testGetMetadata_rule_product_class()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('rule_product_class', $data['rule_types']));
    }

    public function testGetMetadata_name()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('name', $data['rule_types']['rule_product_class']));
    }

    public function testGetMetadata_description()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('description', $data['rule_types']['rule_product_class']));
    }

    public function testGetMetadata_operator_types()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('operator_types', $data['rule_types']['rule_product_class']));
    }

    public function testGetMetadata_condition_types()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('condition_types', $data['rule_types']['rule_product_class']));
    }

    public function testGetMetadata_promotion_types()
    {
        $data = $this->flashSaleService->getMetadata();

        self::assertTrue(array_key_exists('promotion_types', $data['rule_types']['rule_product_class']));
    }
}
