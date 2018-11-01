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

class FlashSaleShoppingProcessorTest extends AbstractServiceTestCase
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

    /** @var FSShoppingProcessor */
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

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleShoppingProcessor = $this->container->get(FSShoppingProcessor::class);
        $this->itemHolderPreprocessors = new ArrayCollection();
        $this->discountProcessors = new ArrayCollection();
        $this->flashSaleRepository = $this->container->get(FlashSaleRepository::class);
        $this->Product = $this->createProduct('テスト商品', 3);
        $this->ProductClass1 = $this->Product->getProductClasses()[0];
    }

    public function testAddDiscountItem_Invalid_FlashSale()
    {
        $mockFlashSaleRepository = $this->getMockBuilder(FlashSaleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockFlashSaleRepository->method('getAvailableFlashSale')
            ->willReturn(null);
        $flashSaleShoppingProcessor = new FSShoppingProcessor($this->entityManager, $mockFlashSaleRepository);

        $Customer = new Customer();
        $Order = new Order();
        $Order->setCustomer($Customer);
        $orderItem = new OrderItem();
        $orderItem->setProductClass($this->ProductClass1);
        $Order->addItem($orderItem);

        $flashSaleShoppingProcessor->addDiscountItem($Order, new PurchaseContext(null, $Customer));
        self::assertCount(1, $Order->getItems());
    }

    public function testAddDiscountItem_Valid_Rules()
    {
        $mockFlashSaleRepository = $this->getMockBuilder(FlashSaleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mRule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $mRule->method('match')->willReturn(true);
        $mRule->method('getDiscountItems')
            ->willReturn([]);

        $mFlashSale = $this->getMockBuilder(FlashSale::class)->getMock();
        $mFlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$mRule]));

        $mockFlashSaleRepository->method('getAvailableFlashSale')
            ->willReturn($mFlashSale);
        $flashSaleShoppingProcessor = new FSShoppingProcessor($this->entityManager, $mockFlashSaleRepository);

        $Customer = new Customer();
        $Order = new Order();
        $Order->setCustomer($Customer);
        $orderItem = new OrderItem();
        $orderItem->setProductClass($this->ProductClass1);
        $orderItem->setQuantity(10);
        $Order->addItem($orderItem);

        $flashSaleShoppingProcessor->addDiscountItem($Order, new PurchaseContext(null, $Customer));

        self::assertCount(1, $Order->getItems());
    }

    public function testAddDiscountItem_Valid()
    {
        $mockFlashSaleRepository = $this->getMockBuilder(FlashSaleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $discountItem = new OrderItem();

        $mRule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $mRule->method('match')->willReturn(true);
        $mRule->method('getDiscountItems')
            ->willReturn([$discountItem]);

        $mFlashSale = $this->getMockBuilder(FlashSale::class)->getMock();
        $mFlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$mRule]));

        $mockFlashSaleRepository->method('getAvailableFlashSale')
            ->willReturn($mFlashSale);
        $flashSaleShoppingProcessor = new FSShoppingProcessor($this->entityManager, $mockFlashSaleRepository);

        $Customer = new Customer();
        $Order = new Order();
        $Order->setCustomer($Customer);
        $orderItem = new OrderItem();
        $orderItem->setProductClass($this->ProductClass1);
        $orderItem->setQuantity(1);
        $Order->addItem($orderItem);

        $flashSaleShoppingProcessor->addDiscountItem($Order, new PurchaseContext(null, $Customer));

        self::assertContains($discountItem, $Order->getItems());
        self::assertContains($discountItem->getProcessorName(), FSShoppingProcessor::class);
//        self::assertEquals($discountItem->getQuantity(), $orderItem->getQuantity());
    }

    public function testRemoveDiscountItem_Valid()
    {
        $mockFlashSaleRepository = $this->getMockBuilder(FlashSaleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $discountItem = new OrderItem();

        $mRule = $this->getMockBuilder(Rule\ProductClassRule::class)->getMock();
        $mRule->method('match')->willReturn(true);
        $mRule->method('getDiscountItems')
            ->willReturn([$discountItem]);

        $mFlashSale = $this->getMockBuilder(FlashSale::class)->getMock();
        $mFlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$mRule]));

        $mockFlashSaleRepository->method('getAvailableFlashSale')
            ->willReturn($mFlashSale);
        $flashSaleShoppingProcessor = new FSShoppingProcessor($this->entityManager, $mockFlashSaleRepository);

        $Customer = new Customer();
        $Order = new Order();
        $Order->setCustomer($Customer);
        $orderItem = new OrderItem();
        $orderItem->setProductClass($this->ProductClass1);
        $Order->addItem($orderItem);

        $flashSaleShoppingProcessor->addDiscountItem($Order, new PurchaseContext(null, $Customer));
        self::assertCount(2, $Order->getItems());
        self::assertContains($discountItem->getProcessorName(), FSShoppingProcessor::class);
        self::assertEquals($discountItem->getQuantity(), $orderItem->getQuantity());

        $flashSaleShoppingProcessor->removeDiscountItem($Order, new PurchaseContext(null, $Customer));
        self::assertCount(1, $Order->getItems());
    }
}
