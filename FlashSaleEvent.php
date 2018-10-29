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

use Eccube\Event\TemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FlashSaleEvent
 * @package Plugin\FlashSale
 */
class FlashSaleEvent implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Mypage/history.twig' => 'mypageHistory',
            'Mypage/index.twig' => 'mypageIndex'
        ];
    }

    /**
     * @param TemplateEvent $event
     */
    public function mypageHistory(TemplateEvent $event)
    {
        $source = $event->getSource();
        $target = '{{ orderItem.price_inc_tax|price }}';
        $change = "{% if orderItem.fs_price %}<del>{{orderItem.price_inc_tax|price}}</del><span class='ec-color-red'>{{orderItem.fs_price|price}}</span>{% else %}{$target}{% endif %}";
        $source = str_replace($target, $change, $source);
        $event->setSource($source);
    }

    /**
     * @param TemplateEvent $event
     */
    public function mypageIndex(TemplateEvent $event)
    {
        $source = $event->getSource();
        $target = "{{ OrderItem.price_inc_tax|price }}";
        $change = "{% if OrderItem.fs_price %}<del>{{OrderItem.price_inc_tax|price}}</del><span class='ec-color-red'>{{OrderItem.fs_price|price}}</span>{% else %}{$target}{% endif %}";
        $source = str_replace($target, $change, $source);

        $target = "Order.MergedProductOrderItems";
        $change = "Order.FsMergedProductOrderItems";
        $source = str_replace($target, $change, $source);

        $event->setSource($source);
    }
}
