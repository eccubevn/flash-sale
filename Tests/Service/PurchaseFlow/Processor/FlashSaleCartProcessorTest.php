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

use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Util\StringUtil;
use Plugin\FlashSale\Service\FlashSaleService;

class FlashSaleCartProcessorTest extends AbstractServiceTestCase
{
    /** @var FlashSaleService */
    protected $flashSaleService;

    /** @var ProductRepository */
    protected $productRepository;

    /** @var ProductClassRepository */
    protected $productClassRepository;

    /** @var CartService */
    protected $cartService;

    /** @var PurchaseFlow */
    protected $purchaseFlow;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleService = $this->container->get(FlashSaleService::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
        $this->productClassRepository = $this->container->get(ProductClassRepository::class);
        $this->cartService = $this->container->get(CartService::class);
        $this->purchaseFlow = $this->container->get(PurchaseFlow::class);
    }

    public function testProcessor()
    {
        $data = $this->createFlashSaleAndRules(__METHOD__.' - test only');
        /** @var Product $Product */
        $Product = $data['Product'];

        foreach ($Product->getProductClasses() as $productClass) {
            $preOrderId = sha1(StringUtil::random(32));

            $this->cartService->addProduct($productClass, 1);
            $this->cartService->setPreOrderId($preOrderId);
            $this->purchaseFlow->validate($this->cartService->getCart(), new PurchaseContext());

            $this->cartService->save();

            $this->expected = $preOrderId;
            $this->actual = $this->cartService->getCart()->getPreOrderId();
            $this->verify();
        }
    }
}
