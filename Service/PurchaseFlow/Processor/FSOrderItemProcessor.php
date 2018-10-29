<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 10/29/2018
 * Time: 11:05 AM
 */

namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;


use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Order;
use Eccube\Service\PurchaseFlow\Processor\AbstractPurchaseProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Eccube\Annotation;

/**
 * @Annotation\ShoppingFlow()
 */
class FSOrderItemProcessor extends AbstractPurchaseProcessor
{
    /**
     * @var FlashSaleRepository
     */
    private $fSRepository;

    /**
     * FSOrderItemProcessor constructor.
     * @param FlashSaleRepository $fSRepository
     */
    public function __construct(FlashSaleRepository $fSRepository)
    {
        $this->fSRepository = $fSRepository;
    }

    /**
     * @param ItemHolderInterface $target
     * @param PurchaseContext $context
     */
    public function commit(ItemHolderInterface $target, PurchaseContext $context)
    {
        if (!$target instanceof Order) {
            return;
        }

        $FlashSale = $this->fSRepository->getAvailableFlashSale();
        foreach ($target->getProductOrderItems() as $productOrderItem) {
            if ($productOrderItem->isProduct()) {
                $ProductClass = $productOrderItem->getProductClass();
                $price = $ProductClass->getPrice02IncTax();
                /** @var RuleInterface $Rule */
                foreach ($FlashSale->getRules() as $Rule) {
                    if (!$Rule->match($ProductClass)) {
                        continue;
                    }
                    foreach ($Rule->getDiscountItems($ProductClass) as $discountItem) {
                        $price += $discountItem->getPrice();
                    }
                }

                $productOrderItem->setFsPrice($price);
            }
        }
    }
}