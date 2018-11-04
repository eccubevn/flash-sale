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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Cart;
use Eccube\Entity\CartItem;
use Eccube\Entity\Customer;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\DiscountProcessor;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\FlashSaleService;
use Plugin\FlashSale\Service\PurchaseFlow\Processor\FSShoppingProcessor;

class FSShoppingProcessorTest extends AbstractServiceTestCase
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
     * @var FSShoppingProcessor
     */
    protected $flashSaleShoppingProcessor;

    /**
     * @var ArrayCollection|ItemHolderPreprocessor[]
     */
    protected $itemHolderPreprocessors;

    /**
     * @var ArrayCollection|DiscountProcessor[]
     */
    protected $discountProcessors;

    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /** @var Product */
    protected $Product;

    /** @var ProductClass */
    protected $ProductClass1;

    protected $purchaseContext;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->purchaseContext = $this->getMockBuilder(PurchaseContext::class)->getMock();
        $this->flashSaleShoppingProcessor = $this->container->get(FSShoppingProcessor::class);
        $this->itemHolderPreprocessors = new ArrayCollection();
        $this->discountProcessors = new ArrayCollection();
        $this->flashSaleRepository = $this->container->get(FlashSaleRepository::class);
        $this->Product = $this->createProduct('テスト商品', 3);
        $this->ProductClass1 = $this->Product->getProductClasses()[0];
    }

    public function testRemoveDiscountItem()
    {
        $Order = new Order();
        $DiscountItem = new OrderItem();
        $DiscountItem->setProcessorName(FSShoppingProcessor::class);
        $this->flashSaleShoppingProcessor->addDiscountItem($Order, $this->purchaseContext);
        $this->assertEquals(0, count($Order->getItems()));
    }

    public function testAddDiscountItem_Scenario0()
    {

        $Order = new Order();
        $this->flashSaleShoppingProcessor->addDiscountItem($Order, $this->purchaseContext);
        $this->assertEquals(0, count($Order->getItems()));
    }

    public function testAddDiscountItem_Scenario1()
    {
        $Order = new Order();
        $Order->setTotal(1000);
        $Order->addFlashSaleDiscount(rand(), 1);
        $this->flashSaleShoppingProcessor->addDiscountItem($Order, $this->purchaseContext);
        $this->assertEquals(1, count($Order->getItems()->getDiscounts()));
        $DiscountItem = $Order->getItems()->getDiscounts()->current();
        $this->assertEquals(-1 * $Order->getFlashSaleTotalDiscount(), $DiscountItem->getPrice());
        $this->assertEquals(FSShoppingProcessor::class, $DiscountItem->getProcessorName());
    }
}
