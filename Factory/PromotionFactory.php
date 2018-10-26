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

namespace Plugin\FlashSale\Factory;

use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;
use Plugin\FlashSale\Entity\PromotionInterface;

class PromotionFactory
{
    /**
     * Create Promotion from array
     *
     * @param array $options
     *
     * @return PromotionInterface
     */
    public function create(array $options = [])
    {
        if (!isset($options['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($options['type']) {
            case ProductClassPricePercentPromotion::TYPE:
                $Promotion = new ProductClassPricePercentPromotion();
                break;
            case ProductClassPriceAmountPromotion::TYPE:
                $Promotion = new ProductClassPriceAmountPromotion();
                break;
            case CartTotalPercentPromotion::TYPE:
                $Promotion = new CartTotalPercentPromotion();
                break;
            case CartTotalAmountPromotion::TYPE:
                $Promotion = new CartTotalAmountPromotion();
                break;

            default:
                throw new \InvalidArgumentException($options['type'].' unsupported');
        }

        if (isset($options['value'])) {
            $Promotion->setValue($options['value']);
        }

        return $Promotion;
    }
}
