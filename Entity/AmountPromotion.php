<?php
namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AmountPromotion extends Promotion implements PromotionInterface
{
    const TYPE = 'amount';

    /**
     * {@inheritdoc}
     *
     * @param $value
     * @return mixed
     */
    public function getDiscountValue($value)
    {
        return $value;
    }
}
