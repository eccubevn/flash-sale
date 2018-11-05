<?php
namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Eccube\Annotation;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;

/**
 * @Annotation\ShoppingFlow()
 */
class FSCartRuleShoppingProcessor implements ItemHolderPreprocessor
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * FSCartRuleShoppingProcessor constructor.
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(FlashSaleRepository $flashSaleRepository)
    {
        $this->flashSaleRepository = $flashSaleRepository;
    }

    /**
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     */
    public function process(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }

        $discount = $FlashSale->getDiscount($itemHolder);
        if (!$discount->getValue()) {
            return;
        }

        $itemHolder->cleanFlashSaleDiscount()->addFlashSaleDiscount($discount->getRuleId(), $discount->getValue());
    }
}
