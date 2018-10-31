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
use Eccube\Entity\Cart;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\DiscountInterface;
use Plugin\FlashSale\Entity\Discount;

/**
 * @ORM\Entity
 */
class CartTotalAmountPromotion extends Promotion
{
    const TYPE = 'promotion_cart_total_amount';

    /**
     * {@inheritdoc}
     *
     * @param $object
     * @return DiscountInterface
     */
    public function getDiscount($object)
    {
        $discount = new Discount();
        $discount->setPromotionId($this->getId());

        if (!$object instanceof Cart && !$object instanceof Order) {
            return $discount;
        }

        $discount->setValue($this->getValue());

        return $discount;
    }
}
