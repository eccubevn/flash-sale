<?php
namespace Plugin\FlashSale\Entity;

use Eccube\Annotation as Eccube;

/**
 * @Eccube\EntityExtension("Eccube\Entity\CartItem")
 */
trait FSCartItemTrait
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
     * Add flash sale discount
     *
     * @param $ruleId
     * @param $discountValue
     * @return $this
     */
    public function addFlashSaleDiscount($ruleId, $discountValue)
    {
        $this->flashSaleDiscount[$ruleId] = $discountValue;
        return $this;
    }

    /**
     * Get $flashSaleDiscount
     *
     * @return int
     */
    public function getFlashSaleDiscount()
    {
        return $this->getProductClass()->getFlashSaleDiscount();
    }

    /**
     * Get flashsale discount * quantity
     *
     * @return int
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
        return (int) ($this->getPrice() - $this->getFlashSaleDiscount());
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
        return (int) ceil($this->getFlashSaleDiscount() * 100 / $this->getPrice());
    }
}