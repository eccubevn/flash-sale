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

use Plugin\FlashSale\Entity\DiscountInterface;

interface PromotionInterface
{
    /**
     * Calculate discount item
     *
     * @param $object
     *
     * @return DiscountInterface
     */
    public function getDiscount($object);
}
