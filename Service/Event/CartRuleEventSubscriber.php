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
use Eccube\Common\EccubeConfig;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\RuleInterface;

class CartRuleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

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
     * @param FlashSaleRepository $flashSaleRepository
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        FlashSaleRepository $flashSaleRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->flashSaleRepository = $flashSaleRepository;
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
            'Cart/index.twig' => 'onTemplateCartIndex',
        ];
    }

    /**
     * Display price of flashsale on cart index template
     *
     * @param TemplateEvent $event
     */
    public function onTemplateCartIndex(TemplateEvent $event)
    {
        $source = $event->getSource();

        $source = str_replace('Cart.totalPrice|price', 'flashSalePrice(Cart, Cart.totalPrice)|raw', $source);

        $event->setSource($source);
    }
}
