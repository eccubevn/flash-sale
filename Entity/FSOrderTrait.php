<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 10/29/2018
 * Time: 1:59 PM
 */

namespace Plugin\FlashSale\Entity;

use Eccube\Annotation\EntityExtension;
use Eccube\Entity\ItemInterface;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait FSOrderTrait
{
    use AbstractShoppingTrait;

    /**
     * @see Order::getMergedProductOrderItems()
     *
     * @return OrderItem[]
     */
    public function getFsMergedProductOrderItems()
    {
        $ProductOrderItems = $this->getProductOrderItems();
        $orderItemArray = [];
        /** @var OrderItem $ProductOrderItem */
        foreach ($ProductOrderItems as $ProductOrderItem) {
            $productClassId = $ProductOrderItem->getProductClass()->getId();
            if (array_key_exists($productClassId, $orderItemArray)) {
                // 同じ規格の商品がある場合は個数をまとめる
                /** @var ItemInterface $OrderItem */
                $OrderItem = $orderItemArray[$productClassId];
                $quantity = $OrderItem->getQuantity() + $ProductOrderItem->getQuantity();
                $OrderItem->setQuantity($quantity);
            } else {
                // 新規規格の商品は新しく追加する
                $OrderItem = new OrderItem();
                $OrderItem
                    ->setProduct($ProductOrderItem->getProduct())
                    ->setProductName($ProductOrderItem->getProductName())
                    ->setProductCode($ProductOrderItem->getProductCode())
                    ->setClassCategoryName1($ProductOrderItem->getClassCategoryName1())
                    ->setClassCategoryName2($ProductOrderItem->getClassCategoryName2())
                    ->setPrice($ProductOrderItem->getPrice())
                    ->setTax($ProductOrderItem->getTax())
                    ->setQuantity($ProductOrderItem->getQuantity())
                    ->setFsPrice((float) $ProductOrderItem->getFsPrice()); //new
                $orderItemArray[$productClassId] = $OrderItem;
            }
        }

        return array_values($orderItemArray);
    }
}
