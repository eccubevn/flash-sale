<?php
/**
 * Created by PhpStorm.
 * User: lqdung
 * Date: 10/29/2018
 * Time: 10:33 AM
 */

namespace Plugin\FlashSale\Entity;

use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\OrderItem")
 */
trait FSOrderItemTrait
{
    /**
     * @var float
     * @ORM\Column(name="fs_price", type="decimal", precision=12, scale=2, nullable=true, options={"default":0})
     */
    private $fsPrice;

    /**
     * @return float
     */
    public function getFsPrice()
    {
        return $this->fsPrice;
    }

    /**
     * @param float $fsPrice
     */
    public function setFsPrice(float $fsPrice): void
    {
        $this->fsPrice = $fsPrice;
    }

    /**
     * @return float
     */
    public function getFsPriceTotal()
    {
        return (empty($this->getFsPrice()) ? $this->getPriceIncTax() : $this->getFsPrice()) * $this->getQuantity();
    }

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
