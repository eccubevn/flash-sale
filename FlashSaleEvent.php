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

use Eccube\Entity\CustomerFavoriteProduct;
use Eccube\Entity\ProductClass;
use Eccube\Event\TemplateEvent;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class FlashSaleEvent
 */
class FlashSaleEvent implements EventSubscriberInterface
{
    /**
     * @var FlashSaleRepository
     */
    private $fSRepository;

    /**
     * FlashSaleEvent constructor.
     *
     * @param FlashSaleRepository $fSRepository
     */
    public function __construct(FlashSaleRepository $fSRepository)
    {
        $this->fSRepository = $fSRepository;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Mypage/history.twig' => 'mypageHistory',
            'Mypage/index.twig' => 'mypageIndex',
            'Mypage/favorite.twig' => 'mypageFavorite',
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
        $target = '{{ OrderItem.price_inc_tax|price }}';
        $change = "{% if OrderItem.fs_price %}<del>{{OrderItem.price_inc_tax|price}}</del><span class='ec-color-red'>{{OrderItem.fs_price|price}}</span>{% else %}{$target}{% endif %}";
        $source = str_replace($target, $change, $source);

        $target = 'Order.MergedProductOrderItems';
        $change = 'Order.FsMergedProductOrderItems';
        $source = str_replace($target, $change, $source);

        $event->setSource($source);
    }

    /**
     * @param TemplateEvent $event
     */
    public function mypageFavorite(TemplateEvent $event)
    {
        $FlashSale = $this->fSRepository->getAvailableFlashSale();
        if (!$event->hasParameter('pagination') || !$FlashSale instanceof FlashSale) {
            return;
        }

        $source = $event->getSource();

        // check match price section
        preg_match('/<p class="ec-favoriteRole__itemPrice">/u', $source, $match, PREG_OFFSET_CAPTURE);
        if (empty($match)) {
            return;
        }
        $index = current($match)[1];
        $firstSection = substr($source, 0, $index);
        $endSection = substr($source, $index);

        // end of price section
        preg_match('/<\/p>/u', $endSection, $match2, PREG_OFFSET_CAPTURE);
        if (empty($match2)) {
            return;
        }

        $index2 = current($match2)[1];
        $middleSection = substr($endSection, 0, $index2);
        $endSection = substr($endSection, $index2);

        $insert = <<<EOT
        {% if fs_data is defined and fs_data[Product.id] is defined %}
<p id="discount" class="ec-color-red ec-font-size-5">
    Sale up to {{ fs_data[Product.id] }} %
</p>
{%endif%}
EOT;
        $newSource = $firstSection.$middleSection.$insert.$endSection;
        $event->setSource($newSource);

        // calculate percent
        $data = [];
        $pagination = $event->getParameter('pagination');
        /** @var CustomerFavoriteProduct $ProductFavorite */
        foreach ($pagination as $ProductFavorite) {
            $Product = $ProductFavorite->getProduct();
            $tmp = [];
            /** @var ProductClass $ProductClass */
            foreach ($Product->getProductClasses() as $ProductClass) {
                /** @var RuleInterface $Rule */
                foreach ($FlashSale->getRules() as $Rule) {
                    if ($Rule->match($ProductClass)) {
                        // discount include tax???
                        $discountItems = $Rule->getDiscountItems($ProductClass);
                        $discountItem = current($discountItems);
                        $discountPrice = $ProductClass->getPrice02IncTax() + $discountItem->getPrice();
                        $discountPercent = 100 - floor($discountPrice * 100 / $ProductClass->getPrice02IncTax());
                        $tmp[$ProductClass->getId()] = $discountPercent;
                    }
                }
            }
            if (count($tmp)) {
                $data[$Product->getId()] = max($tmp);
            }
        }

        if (empty($data)) {
            return;
        }

        $event->setParameter('fs_data', $data);
    }
}
