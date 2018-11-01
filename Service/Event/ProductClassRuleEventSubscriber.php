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

namespace Plugin\FlashSale\Service\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Event\TemplateEvent;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\CartItem;
use Eccube\Common\EccubeConfig;
use Eccube\Twig\Extension\CartServiceExtension;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Service\Rule\RuleInterface;

class ProductClassRuleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var \NumberFormatter
     */
    protected $formatter;

    /**
     * ProductClassRuleEventSubscriber constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->formatter = new \NumberFormatter($this->eccubeConfig['locale'], \NumberFormatter::CURRENCY);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'onTemplateProductDetail',
            'Product/list.twig' => 'onTemplateProductList'
        ];
    }

    /**
     * Change price of product depend on flash sale
     *
     * @param TemplateEvent $event
     */
    public function onTemplateProductDetail(TemplateEvent $event)
    {
        if (!$event->hasParameter('Product')) {
            return;
        }

        $json = [];

        /** @var ProductClass $ProductClass */
        foreach ($event->getParameter('Product')->getProductClasses() as $ProductClass) {
            $discountValue = $ProductClass->getFlashSaleDiscount();
            if ($discountValue) {
                $discountPrice = $ProductClass->getFlashSaleDiscountPrice();
                $discountPercent = $ProductClass->getFlashSaleDiscountPercent();
                $json[$ProductClass->getId()] = [
                    'message' => '<p class="ec-color-red"><span>'.$this->formatter->formatCurrency($discountPrice, $this->eccubeConfig['currency']).'</span> (-'.$discountPercent.'%)</p>',
                ];
            }
        }

        if (empty($json)) {
            return;
        }

        $event->setParameter('ProductFlashSale', json_encode($json));
        $event->addSnippet('@FlashSale/default/detail.twig');
    }

    /**
     * Change price of product depend on flash sale
     *
     * @param TemplateEvent $event
     */
    public function onTemplateProductList(TemplateEvent $event)
    {
        if (!$event->hasParameter('pagination')) {
            return;
        }

        $json = [];

        /** @var Product $Product */
        foreach ($event->getParameter('pagination') as $Product) {
            /** @var ProductClass $ProductClass */
            foreach ($Product->getProductClasses() as $ProductClass) {
                $discountValue = $ProductClass->getFlashSaleDiscount();
                if ($discountValue) {
                    $discountPrice = $ProductClass->getFlashSaleDiscountPrice();
                    $discountPercent = $ProductClass->getFlashSaleDiscountPercent();
                    $json[$ProductClass->getId()] = [
                        'message' => '<p class="ec-color-red"><span>'.$this->formatter->formatCurrency($discountPrice, $this->eccubeConfig['currency']).'</span> (-'.$discountPercent.'%)</p>',
                    ];
                }
            }
        }

        if (empty($json)) {
            return;
        }

        $event->setParameter('ProductFlashSale', json_encode($json));
        $event->addSnippet('@FlashSale/default/list.twig');
    }
}
