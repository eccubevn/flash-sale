<?php

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
            'setting' => [
                'children' => [
                    'FlashSale' => [
                        'id' => 'flash_sales_admin',
                        'url' => 'flash_sale_admin_list',
                        'name' => 'Flash sale',
                    ],
                ],
            ],
        ];
    }
}
