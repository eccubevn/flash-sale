<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 10/29/2018
 * Time: 11:05 AM
 */

namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Eccube\Annotation;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Order;
use Eccube\Service\PurchaseFlow\Processor\AbstractPurchaseProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;

/**
 * @Annotation\ShoppingFlow()
 */
class FSOrderItemProcessor extends AbstractPurchaseProcessor
{
    /**
     * @param ItemHolderInterface $target
     * @param PurchaseContext $context
     */
    public function commit(ItemHolderInterface $target, PurchaseContext $context)
    {
        if (!$target instanceof Order) {
            return;
        }

        foreach ($target->getProductOrderItems() as $productOrderItem) {
            if ($productOrderItem->isProduct()) {
                $productOrderItem->setFsPrice($productOrderItem->getFlashSaleTotalDiscountPrice());
            }
        }
    }
}
