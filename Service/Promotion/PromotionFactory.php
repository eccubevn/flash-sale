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

namespace Plugin\FlashSale\Service\Promotion;

use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;

class PromotionFactory
{
    /**
     * Create Promotion from array
     *
     * @param array $data
     *
     * @return PromotionInterface
     */
    public static function createFromArray(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($data['type']) {
            case ProductClassPricePercentPromotion::TYPE:
                $Promotion = new ProductClassPricePercentPromotion();
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
