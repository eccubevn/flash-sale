<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 11/2/2018
 * Time: 10:27 AM
 */

namespace Plugin\FlashSale\Entity;

/**
 * Trait AbstractShoppingTrait
 * @uses FSCartTrait
 * @uses FSOrderTrait
 * @package Plugin\FlashSale\Entity
 */
trait AbstractShoppingTrait
{
    /**
     * @var array
     */
    protected $flashSaleDiscount = [];

    /**
     * Clean discount from flash sale
     *
     * @return $this
     */
    public function cleanFlashSaleDiscount()
    {
        $this->flashSaleDiscount = [];
        return $this;
    }

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
        foreach ($this->getItems() as $item) {
            $totalDiscount += $item->getFlashSaleTotalDiscount();
        }

        $sum = array_sum($this->flashSaleDiscount);

        if ($sum > $totalDiscount) {
            return $totalDiscount;
        }

        return $sum + $totalDiscount;
    }

    /**
     * Get total discount price
     *
     * @return int
     */
    public function getFlashSaleTotalDiscountPrice()
    {
        return (int) ($this->getTotalPrice() - $this->getFlashSaleTotalDiscount());
    }
}
