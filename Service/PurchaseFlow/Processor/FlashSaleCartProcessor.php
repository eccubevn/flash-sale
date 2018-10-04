<?php
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
