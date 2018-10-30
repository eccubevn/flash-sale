<?php
namespace Plugin\FlashSale\Entity;

use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * @var array
     */
    protected $flashSaleDiscount = [];

    /**
     * Add an discount
     *
     * @param int $ruleId
     * @param int $discountValue
     * @return $this
     */
    public function addFlashSaleDiscount(int $ruleId, int $discountValue)
    {
        $this->flashSaleDiscount[$ruleId] = $discountValue;

        return $this;
    }

    /**
     * Get $flashSaleTotalDiscount
     *
     * @return string
     */
    public function getFlashSaleTotalDiscount()
    {
        $totalDiscount = 0;
        foreach ($this->getItems() as $OrderItem) {
            $totalDiscount += $OrderItem->getFlashSaleDiscount() * $OrderItem->getQuantity();
        }

        return array_sum($this->flashSaleDiscount) + $totalDiscount;
    }

    /**
     * Get total discount price
     *
     * @return int
     */
    public function getFlashSaleTotalDiscountPrice()
    {
        return (int) $this->getTotalPrice() - $this->getFlashSaleTotalDiscount();
    }
}