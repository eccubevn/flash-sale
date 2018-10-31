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
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\DiscountInterface;
use Plugin\FlashSale\Entity\Discount;

/**
 * @ORM\Entity
 */
class ProductClassPriceAmountPromotion extends Promotion
{
    const TYPE = 'promotion_product_class_price_amount';

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

        if (!$object instanceof ProductClass) {
            return $discount;
        }

        $discount->setValue($this->getValue());

        return $discount;
    }
}
