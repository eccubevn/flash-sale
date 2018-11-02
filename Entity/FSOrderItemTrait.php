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
    use AbstractItemTrait;

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
        return (is_null($this->getFsPrice()) ? $this->getPriceIncTax() : $this->getFsPrice()) * $this->getQuantity();
    }
}
