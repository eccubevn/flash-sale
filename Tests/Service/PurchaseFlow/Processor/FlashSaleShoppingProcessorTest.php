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

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Customer;
use Eccube\Entity\Order;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Util\StringUtil;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\FlashSaleService;
use Plugin\FlashSale\Service\PurchaseFlow\Processor\FlashSaleShoppingProcessor;

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

    /** @var FlashSaleShoppingProcessor */
    protected $flashSaleShoppingProcessor;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        /*$this->flashSaleService = $this->container->get(FlashSaleService::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
        $this->productClassRepository = $this->container->get(ProductClassRepository::class);
        $this->cartService = $this->container->get(CartService::class);
        $this->purchaseFlow = $this->container->get(PurchaseFlow::class);
        $registry = $this->getMockBuilder('Symfony\Bridge\Doctrine\RegistryInterface')->getMock();
        $mockFlashSaleRepo = $this->getMockBuilder(FlashSaleRepository::class)

            ->setConstructorArgs([$this->container->get('doctrine')])
            ->setMethods([
                'getAvailableFlashSale' => function(){
                return new FlashSale();
                }
            ])
            ->getMock();
        dump($mockFlashSaleRepo->getAvailableFlashSale());
        die;

        $this->flashSaleShoppingProcessor = new FlashSaleShoppingProcessor($this->container->get(EntityManagerInterface::class), $mockFlashSaleRepo);
        */
    }

    public function testRemoveDiscountItem()
    {

    }

    public function testProcessor()
    {

    }
}
