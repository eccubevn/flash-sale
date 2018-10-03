<?php
namespace Plugin\FlashSale\Entity\Promotion;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ItemInterface;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\OrderItem;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Entity\Promotion;


/**
 * @ORM\Entity
 */
class ProductClassPricePercentPromotion extends Promotion implements PromotionInterface
{
    const TYPE = 'promotion_product_class_price_percent';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Set $entityManager
     *
     * @param EntityManagerInterface $entityManager
     * @return $this
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param $value
     * @return ItemInterface[]
     */
    public function getDiscountItems($ProductClass)
    {
        if (!$ProductClass instanceof ProductClass) {
            throw new \InvalidArgumentException(sprintf('$ProductClass must be %s type', ProductClass::class));
        }

        $DiscountType = $this->entityManager->find(OrderItemType::class,  OrderItemType::DISCOUNT);
        $TaxInclude = $this->entityManager->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager->find(TaxType::class, TaxType::NON_TAXABLE);
        
        $price = $ProductClass->getPrice02() / 100 * $this->getValue();

        $OrderItem = new OrderItem();
        $OrderItem->setProductName($DiscountType->getName())
            ->setPrice(-1 * $price)
            ->setQuantity(1)
            ->setTax(0)
            ->setTaxRate(0)
            ->setTaxRuleId(null)
            ->setRoundingType(null)
            ->setOrderItemType($DiscountType)
            ->setTaxDisplayType($TaxInclude)
            ->setTaxType($Taxation);

        return $OrderItem;
    }
}
