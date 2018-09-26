<?php
namespace Plugin\FlashSale\Service\Promotion;

interface PromotionInterface
{
    /**
     * @return mixed
     */
    public function getDiscountValue($value);
}
