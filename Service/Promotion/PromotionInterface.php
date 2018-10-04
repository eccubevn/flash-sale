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

interface PromotionInterface
{
    /**
     * Calculate discount items
     *
     * @param $object
     *
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($object);
}
