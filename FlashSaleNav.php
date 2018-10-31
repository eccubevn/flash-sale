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

use Eccube\Common\EccubeNav;

class FlashSaleNav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'product' => [
                'children' => [
                    'flash_sales_admin' => [
                        'url' => 'flash_sale_admin_list',
                        'name' => 'flash_sale.admin.nav.name',
                    ],
                ],
            ],
        ];
    }
}
