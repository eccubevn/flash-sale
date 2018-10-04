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

namespace Plugin\FlashSale\Test\Service;

use Doctrine\Common\Annotations\AnnotationReader;
use Eccube\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Service\FlashSaleService;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;

class FlashSaleServiceTest extends AbstractServiceTestCase
{
    /** @var  FlashSaleService */
    protected $flashSaleService;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleService = new FlashSaleService(new AnnotationReader(), new DiscriminatorManager());
    }

    public function testGetMetadata()
    {
        $data = $this->flashSaleService->getMetadata();
        $this->expected = true;
        $this->actual = array_key_exists('rule_types', $data);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('rule_product_class', $data['rule_types']);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('name', $data['rule_types']['rule_product_class']);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('description', $data['rule_types']['rule_product_class']);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('operator_types', $data['rule_types']['rule_product_class']);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('condition_types', $data['rule_types']['rule_product_class']);
        $this->verify();

        $this->expected = true;
        $this->actual = array_key_exists('promotion_types', $data['rule_types']['rule_product_class']);
        $this->verify();
    }
}