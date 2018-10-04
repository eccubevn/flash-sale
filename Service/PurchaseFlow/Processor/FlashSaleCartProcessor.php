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

namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;

class FlashSaleCartProcessor implements ItemHolderPreprocessor
{
    /**
     * {@inheritdoc}
     *
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     */
    public function process(
        ItemHolderInterface $itemHolder,
        PurchaseContext $context
    ) {
        foreach ($itemHolder->getItems() as $item) {
            $item->setPrice(1);
        }
    }
}
