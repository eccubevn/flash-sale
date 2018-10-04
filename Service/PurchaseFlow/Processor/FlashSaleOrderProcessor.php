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
use Eccube\Service\PurchaseFlow\DiscountProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Annotation;

/**
 * @Annotation\ShoppingFlow()
 */
class FlashSaleOrderProcessor implements DiscountProcessor
{
    protected $entityManager;

    public function __construct(\Doctrine\ORM\EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function removeDiscountItem(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        foreach ($itemHolder->getItems() as $item) {
            if ($item->getProcessorName() == static::class) {
                $itemHolder->removeOrderItem($item);
                $this->entityManager->remove($item);
            }
        }
    }

    public function addDiscountItem(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
//        foreach ($itemHolder->getItems() as $item) {
//            $item->setPrice(1);
//        }
        $DiscountType = $this->entityManager->find(\Eccube\Entity\Master\OrderItemType::class, \Eccube\Entity\Master\OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(\Eccube\Entity\Master\TaxDisplayType::class, \Eccube\Entity\Master\TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(\Eccube\Entity\Master\TaxType::class, \Eccube\Entity\Master\TaxType::NON_TAXABLE);

        $OrderItem = new \Eccube\Entity\OrderItem();
        $OrderItem->setProductName($DiscountType->getName())
            ->setPrice(-200)
            ->setQuantity(1)
            ->setTax(0)
            ->setTaxRate(0)
            ->setTaxRuleId(null)
            ->setRoundingType(null)
            ->setOrderItemType($DiscountType)
            ->setTaxDisplayType($TaxInclude)
            ->setTaxType($Taxation)
            ->setOrder($itemHolder)
            ->setProcessorName(static::class);
        $itemHolder->addItem($OrderItem);
    }
}
