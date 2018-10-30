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

namespace Plugin\FlashSale;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Event\TemplateEvent;
use Eccube\Twig\Extension\CartServiceExtension;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;

class FlashSaleEvent implements EventSubscriberInterface
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * FlashSaleEvent constructor.
     *
     * @param \Twig_Environment $twig
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(
        \Twig_Environment $twig,
        FlashSaleRepository $flashSaleRepository
    ) {
        $this->twig = $twig;
        $this->flashSaleRepository = $flashSaleRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Block/header.twig' => ['onTemplateBlockHeader', 0],
            'Block/cart.twig' => [
                ['onTemplateBlockCart', 0],
                ['onTemplateTotalPrice', 100]
            ],
            'Cart/index.twig' => [
                ['onTemplateCartIndex', 0],
                ['onTemplateTotalPrice', 100]
            ],
            'Shopping/index.twig' => ['onTemplateShoppingIndex', 0],
            'Shopping/shipping_multiple.twig' => ['onTemplateShoppingIndex', 0],
            'Shopping/confirm.twig' => ['onTemplateShoppingIndex', 0],
        ];
    }

    /**
     * Make Block/cart.twig able to dispatch template event
     *
     * @param TemplateEvent $event
     */
    public function onTemplateBlockHeader(TemplateEvent $event)
    {
        $source = $event->getSource();
        $source = str_replace(
            '{{ include(\'Block/cart.twig\') }}',
            '{{ include_dispatch(\'Block/cart.twig\') }}',
            $source
        );
        $event->setSource($source);
    }

    /**
     * Display price of flashsale on block cart template
     *
     * @param TemplateEvent $event
     */
    public function onTemplateBlockCart(TemplateEvent $event)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }

        $source = $event->getSource();
        $source = str_replace(
            '{{ CartItem.price|price }}',
            '{% if CartItem.getFlashSaleDiscount() %} <del>{{ CartItem.price|price }}</del><span class="ec-color-red">{{ CartItem.getFlashSaleDiscountPrice()|price }} ({{ CartItem.getFlashSaleDiscountPercent() }}%)</span> {% else %} {{ CartItem.price|price }} {% endif %}',
            $source
        );
        $source = str_replace(
            '{{ totalPrice|price }}',
            '{% if totalDiscountPrice is defined %}<del>{{ totalPrice|price }}</del><span>{{ (totalPrice - totalDiscountPrice)|price }}</span>{% else %} {{ totalPrice|price }} {% endif %}',
            $source
        );
        $event->setSource($source);
    }

    /**
     * Assign total discount price to template
     *
     * @param TemplateEvent $event
     */
    public function onTemplateTotalPrice(TemplateEvent $event)
    {
        if ($event->hasParameter('totalDiscountPrice')) {
            return;
        }

        $totalDiscountPrice = 0;
        $Carts = $this->twig->getExtension(CartServiceExtension::class)->get_all_carts();
        foreach ($Carts as $Cart) {
            $totalDiscountPrice += $Cart->getFlashSaleTotalDiscount();
        }

        if ($totalDiscountPrice) {
            $event->setParameter('totalDiscountPrice', $totalDiscountPrice);
        }
    }

    /**
     * Display price of flashsale on cart index template
     *
     * @param TemplateEvent $event
     */
    public function onTemplateCartIndex(TemplateEvent $event)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }

        $source = $event->getSource();
        $source = str_replace(
            '{{ CartItem.price|price }}',
            '{% if CartItem.getFlashSaleDiscount() %} <del>{{ CartItem.price|price }}</del><span class="ec-color-red">{{ CartItem.getFlashSaleDiscountPrice()|price }} ({{ CartItem.getFlashSaleDiscountPercent() }}%)</span> {% else %} {{ CartItem.price|price }} {% endif %}',
            $source
        );
        $source = str_replace(
            '{{ CartItem.total_price|price }}',
            '{% if CartItem.getFlashSaleTotalDiscount() %} <del>{{ CartItem.total_price|price }}</del><span class="ec-color-red">{{ CartItem.getFlashSaleTotalDiscountPrice()|price }}</span> {% else %} {{ CartItem.total_price|price }} {% endif %}',
            $source
        );
        $source = str_replace(
            '{{ Cart.totalPrice|price }}',
            '{% if Cart.getFlashSaleTotalDiscount() %} <del>{{ Cart.totalPrice|price }}</del><span class="ec-color-red">{{ Cart.getFlashSaleTotalDiscountPrice()|price }}</span> {% else %} {{ Cart.totalPrice|price }} {% endif %}',
            $source
        );

        $replace = '
        {% if totalDiscountPrice is defined %}
            {% set totalPriceWithFlashSaleDiscount = "<del>" ~ totalPrice|price ~ "</del><span>" ~ (totalPrice - totalDiscountPrice)|price ~ "</span>" %}
            {{ \'front.cart.total_price\'|trans({ \'%price%\': totalPriceWithFlashSaleDiscount })|raw }}
        {% else %}
            {{ \'front.cart.total_price\'|trans({ \'%price%\': totalPrice|price })|raw }}
        {% endif %}
        ';
        $source = str_replace(
            '{{ \'front.cart.total_price\'|trans({ \'%price%\': totalPrice|price })|raw }}',
            $replace,
            $source
        );
        $event->setSource($source);
    }

    /**
     * Display price of flashsale on shopping index template
     *
     * @param TemplateEvent $event
     */
    public function onTemplateShoppingIndex(TemplateEvent $event)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }

        $source = $event->getSource();
        $source = str_replace(
            '{{ orderItem.priceIncTax|price }}',
            '{% if orderItem.getFlashSaleDiscount() %} <del>{{ orderItem.priceIncTax|price }}</del><span class="ec-color-red">{{ orderItem.getFlashSaleDiscountPrice()|price }}({{ orderItem.getFlashSaleDiscountPercent() }}%)</span> {% else %} {{ orderItem.priceIncTax|price }} {% endif %}',
            $source
        );
        $source = str_replace(
            '{{ orderItem.totalPrice|price }}',
            '{% if orderItem.getFlashSaleTotalDiscount() %} <del>{{ orderItem.totalPrice|price }}</del><span class="ec-color-red">{{ orderItem.getFlashSaleTotalDiscountPrice()|price }}</span> {% else %} {{ orderItem.totalPrice|price }} {% endif %}',
            $source
        );
        $event->setSource($source);
    }
}
