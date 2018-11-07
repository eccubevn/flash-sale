<?php
namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Eccube\Entity\Order;
use Eccube\Service\PurchaseFlow\Processor\DeliveryFeePreprocessor;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\Processor\DeliveryFeeFreeByShippingPreprocessor;

/**
 * @codeCoverageIgnore
 */
class FSDeliveryFeeFreeByShippingPreprocessor extends DeliveryFeeFreeByShippingPreprocessor
{
    /**
     * {@inheritdoc}
     *
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     */
    public function process(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        if (!($this->BaseInfo->getDeliveryFreeAmount() || $this->BaseInfo->getDeliveryFreeQuantity())) {
            return;
        }

        // Orderの場合はお届け先ごとに判定する.
        if ($itemHolder instanceof Order) {
            /** @var Order $Order */
            $Order = $itemHolder;
            foreach ($Order->getShippings() as $Shipping) {
                $isFree = false;
                $total = 0;
                $quantity = 0;
                foreach ($Shipping->getProductOrderItems() as $Item) {
                    $total += $Item->getFlashSaleDiscountPrice() * $Item->getQuantity();
                    $quantity += $Item->getQuantity();
                }
                // 送料無料（金額）を超えている
                if ($this->BaseInfo->getDeliveryFreeAmount()) {
                    if ($total >= $this->BaseInfo->getDeliveryFreeAmount()) {
                        $isFree = true;
                    }
                }
                // 送料無料（個数）を超えている
                if ($this->BaseInfo->getDeliveryFreeQuantity()) {
                    if ($quantity >= $this->BaseInfo->getDeliveryFreeQuantity()) {
                        $isFree = true;
                    }
                }
                if ($isFree) {
                    foreach ($Shipping->getOrderItems() as $Item) {
                        if ($Item->getProcessorName() == DeliveryFeePreprocessor::class) {
                            $Item->setQuantity(0);
                        }
                    }
                }
            }
        }
    }
}
