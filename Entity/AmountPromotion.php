<?php
namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Promotion\PromotionInterface;

class AmountPromotion extends Promotion implements PromotionInterface
{
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
