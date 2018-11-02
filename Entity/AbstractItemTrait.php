<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 11/2/2018
 * Time: 10:44 AM
 */

namespace Plugin\FlashSale\Entity;

/**
 * Trait AbstractItemTrait
 * @uses FSCartItemTrait
 * @uses FSOrderItemTrait
 * @package Plugin\FlashSale\Entity
 */
trait AbstractItemTrait
{
    /**
     * Get $flashSaleDiscount
     *
     * @return int
     */
    public function getFlashSaleDiscount()
    {
        if (!$this->getProductClass()) {
            return 0;
        }

        return $this->getProductClass()->getFlashSaleDiscount();
    }

    /**
     * Get flashsale discount * quantity
     */
    public function getFlashSaleTotalDiscount()
    {
        return $this->getFlashSaleDiscount() * $this->getQuantity();
    }

    /**
     * Get discount price
     *
     * @return int
     */
    public function getFlashSaleDiscountPrice()
    {
        return (int) ($this->getPriceIncTax() - $this->getFlashSaleDiscount());
    }

    /**
     * Get discount total price
     *
     * @return int
     */
    public function getFlashSaleTotalDiscountPrice()
    {
        return (int) ($this->getFlashSaleDiscountPrice() * $this->getQuantity());
    }

    /**
     * Get discount percent
     *
     * @return int
     */
    public function getFlashSaleDiscountPercent()
    {
        return (int) ceil($this->getFlashSaleDiscount() * 100 / $this->getPriceIncTax());
    }
}
