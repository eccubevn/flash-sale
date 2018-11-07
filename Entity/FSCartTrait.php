<?php

/*
 * This file is part of the Flash Sale plugin
 *
 * Copyright(c) ECCUBE VN LAB. All Rights Reserved.
 *
 * https://www.facebook.com/groups/eccube.vn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Entity;

use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Cart")
 */
trait FSCartTrait
{
    use AbstractShoppingTrait;
}
