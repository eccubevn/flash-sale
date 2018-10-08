<?php
namespace Plugin\FlashSale\Service\PurchaseFlow\Processor;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Service\PurchaseFlow\DiscountProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Annotation;
use Eccube\Entity\OrderItem;
use Eccube\Service\PurchaseFlow\ProcessResult;
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
     * @return ProcessResult|null
     */
    public function addDiscountItem(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return null;
        }

        /** @var RuleInterface $Rule */
        foreach ($FlashSale->getRules() as $Rule) {
            /** @var OrderItem $OrderItem */
            foreach ($itemHolder->getItems() as $OrderItem) {
                if (!$Rule->match($OrderItem->getProductClass())) {
                    continue;
                }

                $DiscountItems = $Rule->getDiscountItems($OrderItem->getProductClass());
                foreach ($DiscountItems as $DiscountItem) {
                    $DiscountItem->setProcessorName(static::class)
                        ->setOrder($itemHolder);
                    $itemHolder->addItem($DiscountItem);
                }
            }
        }

        return null;
    }
}
