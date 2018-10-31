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

use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Entity\PromotionInterface;

class PromotionFactory
{
    /**
     * Create Promotion from array
     *
     * @param array $data
     *
     * @return PromotionInterface
     */
    public function create(array $data = [])
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($data['type']) {
            case Promotion\ProductClassPricePercentPromotion::TYPE:
                $Promotion = new Promotion\ProductClassPricePercentPromotion();
                break;
            case Promotion\ProductClassPriceAmountPromotion::TYPE:
                $Promotion = new Promotion\ProductClassPriceAmountPromotion();
                break;
            case Promotion\CartTotalPercentPromotion::TYPE:
                $Promotion = new Promotion\CartTotalPercentPromotion();
                break;
            case Promotion\CartTotalAmountPromotion::TYPE:
                $Promotion = new Promotion\CartTotalAmountPromotion();
                break;

            default:
                throw new \InvalidArgumentException($data['type'].' unsupported');
        }

        if (isset($data['value'])) {
            $Promotion->setValue($data['value']);
        }

        return $Promotion;
    }
}
