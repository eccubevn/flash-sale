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
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Service\Rule\RuleInterface;

/**
 * @Annotation\ShoppingFlow()
 */
class FlashSaleShoppingProcessor implements DiscountProcessor
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * FlashSaleShoppingProcessor constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FlashSaleRepository $flashSaleRepository
    ) {
        $this->entityManager = $entityManager;
        $this->flashSaleRepository = $flashSaleRepository;
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
            if ($item->getProcessorName() == static::class) {
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
