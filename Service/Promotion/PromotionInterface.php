<?php
namespace Plugin\FlashSale\Service\Promotion;

interface PromotionInterface
{
    /**
     * Calculate discount items
     *
     * @param $object
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($object);
}
