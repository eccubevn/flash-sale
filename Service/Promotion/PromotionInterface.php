<?php
namespace Plugin\FlashSale\Service\Promotion;

interface PromotionInterface
{
    /**
     * @return mixed
     */
    public function getDiscountValue($value);

    /**
     * Get attributes
     *
     * @return array
     */
    public function getAttributes(): array ;
}
