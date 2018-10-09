<?php
namespace Plugin\FlashSale\Service\Rule;

use PHPUnit\Framework\MockObject\MockObject;
use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\ProductClass;
use Eccube\Entity\CartItem;
use Eccube\Entity\OrderItem;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;
use Plugin\FlashSale\Service\Twig\Extension\TwigExtension;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;


class TwigExtensionTest extends AbstractServiceTestCase
{
    /**
     * @var TwigExtension
     */
    protected $twigExtension;

    /**
     * @var MockObject
     */
    protected $Rule;

    /**
     * @var MockObject
     */
    protected $FlashSale;

    /**
     * @var MockObject
     */
    protected $flashSaleRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleRepository = $this->getMockBuilder(FlashSaleRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->FlashSale = $this->getMockBuilder(FlashSale::class)->getMock();
        $this->Rule = $this->getMockBuilder(ProductClassRule::class)->getMock();

        $this->twigExtension = new TwigExtension($this->flashSaleRepository, $this->container->get(EccubeConfig::class));

    }

    public function testGetFunctions()
    {
        self::assertCount(1, $this->twigExtension->getFunctions());
        $twigFunction = current($this->twigExtension->getFunctions());
        self::assertEquals($twigFunction->getName(), 'flashSalePrice');
    }

    public function testGetFlashSalePriceNotAvailableFlashSale()
    {
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02(100);
        $ProductClass->setPrice02IncTax(108);
        $CartItem = new CartItem();
        $CartItem->setProductClass($ProductClass);
        $CartItem->setPrice(108);

        $this->flashSaleRepository->expects($this->any())
            ->method('getAvailableFlashSale')
            ->willReturn(null);

        $this->actual = $this->twigExtension->getFlashSalePrice($CartItem, $CartItem->getPrice());
        $this->expected = '￥108';
        $this->verify();
    }

    public function testGetFlashSalePriceNotMatchFlashSale()
    {
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02(100);
        $ProductClass->setPrice02IncTax(108);
        $CartItem = new CartItem();
        $CartItem->setProductClass($ProductClass);
        $CartItem->setPrice(108);

        $this->flashSaleRepository->expects($this->any())
            ->method('getAvailableFlashSale')
            ->willReturn($this->FlashSale);
        $this->Rule->method('match')->willReturn(false);
        $this->FlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$this->Rule]));

        $this->actual = $this->twigExtension->getFlashSalePrice($CartItem, $CartItem->getPrice());
        $this->expected = '￥108';
        $this->verify();
    }

    public function testGetFlashSalePriceCartItem()
    {
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02(100);
        $ProductClass->setPrice02IncTax(108);
        $CartItem = new CartItem();
        $CartItem->setProductClass($ProductClass);
        $CartItem->setPrice(108);
        $DiscountItem = new OrderItem();
        $DiscountItem->setPrice(-20);

        $this->flashSaleRepository->expects($this->any())
            ->method('getAvailableFlashSale')
            ->willReturn($this->FlashSale);
        $this->Rule->method('match')->willReturn(true);
        $this->Rule->method('getDiscountItems')->willReturn([$DiscountItem]);
        $this->FlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$this->Rule]));

        $this->actual = $this->twigExtension->getFlashSalePrice($CartItem, $CartItem->getPrice());
        $this->expected = '<del>￥108</del><span class=\'ec-color-red\'>￥88 (-19%)</span>';
        $this->verify();
    }

    public function testGetFlashSalePriceOrderItem()
    {
        $ProductClass = new ProductClass();
        $ProductClass->setPrice02(100);
        $ProductClass->setPrice02IncTax(108);
        $CartItem = new OrderItem();
        $CartItem->setProductClass($ProductClass);
        $CartItem->setPrice(108);
        $DiscountItem = new OrderItem();
        $DiscountItem->setPrice(-20);

        $this->flashSaleRepository->expects($this->any())
            ->method('getAvailableFlashSale')
            ->willReturn($this->FlashSale);
        $this->Rule->method('match')->willReturn(true);
        $this->Rule->method('getDiscountItems')->willReturn([$DiscountItem]);
        $this->FlashSale->method('getRules')
            ->willReturn(new ArrayCollection([$this->Rule]));

        $this->actual = $this->twigExtension->getFlashSalePrice($CartItem, $CartItem->getPrice());
        $this->expected = '<del>￥108</del><span class=\'ec-color-red\'>￥88 (-19%)</span>';
        $this->verify();
    }
}