<?php
namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\DiscountProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Annotation;
use Eccube\Entity\OrderItem;
use Eccube\Service\PurchaseFlow\ProcessResult;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;

class FSShoppingProcessor implements DiscountProcessor
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * FlashSaleShoppingProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     */
    public function removeDiscountItem(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        /** @var OrderItem $item */
        foreach ($itemHolder->getItems() as $item) {
            if ($item->isDiscount() && $item->getProcessorName() == static::class) {
                $itemHolder->removeOrderItem($item);
                $this->entityManager->remove($item);
            }
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     *
     * @return ProcessResult|null
     */
    public function addDiscountItem(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        $discountValue = $itemHolder->getFlashSaleTotalDiscount();
        if ($discountValue) {
            $DiscountType = $this->entityManager->find(OrderItemType::class, OrderItemType::DISCOUNT);
            $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
            $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);

            $OrderItem = new OrderItem();
            $OrderItem->setProductName($DiscountType->getName())
                ->setPrice(-1 * $discountValue)
                ->setQuantity(1)
                ->setTax(0)
                ->setTaxRate(0)
                ->setTaxRuleId(null)
                ->setRoundingType(null)
                ->setOrderItemType($DiscountType)
                ->setTaxDisplayType($TaxInclude)
                ->setTaxType($Taxation)
                ->setProcessorName(static::class)
                ->setOrder($itemHolder);
            $itemHolder->addItem($OrderItem);
        }

        return null;
    }
}
