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

namespace Plugin\FlashSale\Entity\Promotion;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ItemInterface;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\OrderItem;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Entity\Promotion;

/**
 * @ORM\Entity
 */
class ProductClassPricePercentPromotion extends Promotion implements PromotionInterface
{
    const TYPE = 'promotion_product_class_price_percent';

    /**
     * {@inheritdoc}
     *
     * @param $value
     *
     * @return ItemInterface[]
     */
    public function getDiscountItems($ProductClass)
    {
        if (!$ProductClass instanceof ProductClass) {
            return [];
        }

        $DiscountType = $this->getEntityManager()->find(OrderItemType::class, OrderItemType::DISCOUNT);
        $TaxInclude = $this->getEntityManager()->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->getEntityManager()->find(TaxType::class, TaxType::NON_TAXABLE);

        $price = floor($ProductClass->getPrice02IncTax() / 100 * $this->getValue());

        $OrderItem = new OrderItem();
        $OrderItem->setProductName($DiscountType->getName())
            ->setPrice(-1 * $price)
            ->setQuantity(1)
            ->setTax(0)
            ->setTaxRate(0)
            ->setTaxRuleId(null)
            ->setRoundingType(null)
            ->setOrderItemType($DiscountType)
            ->setTaxDisplayType($TaxInclude)
            ->setTaxType($Taxation);

        return [$OrderItem];
    }
}
