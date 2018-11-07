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

use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
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

    public function testGetCategoryName()
    {
        $Product = $this->createProduct('FlashSaleProductTest' . time());
        $categoryNames = [];
        /** @var ProductCategory $productCategory */
        foreach ($Product->getProductCategories() as $productCategory) {
            $categoryNames[$productCategory->getCategoryId()] = $productCategory->getCategory()->getName();
        }
        $actual = $this->flashSaleService->getCategoryName(array_keys($categoryNames));
        $this->assertEquals(array_keys($categoryNames), array_column($actual, 'id'));
        $this->assertEquals(array_values($categoryNames), array_column($actual, 'name'));
    }

    public function testGetProductClassName()
    {
        $Product = $this->createProduct('FlashSaleProductTest' . time());
        $productClasses = [];
        /** @var ProductClass $ProductClass */
        foreach ($Product->getProductClasses() as $ProductClass) {
            $productClasses[] = [
                'id' => $ProductClass->getId(),
                'name' => $ProductClass->getProduct()->getName(),
                'class_name1' => $ProductClass->getClassCategory1() ? $ProductClass->getClassCategory1()->getName() : null,
                'class_name2' => $ProductClass->getClassCategory2() ? $ProductClass->getClassCategory2()->getName() : null,
            ];
        }
        $actual = $this->flashSaleService->getProductClassName(array_column($productClasses, 'id'));
        $this->assertEquals($productClasses, $actual);
    }
}
