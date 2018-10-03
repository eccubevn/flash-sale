<?php
namespace Plugin\FlashSale\Service\Promotion;

use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;

class PromotionFactory
{
    /**
     * Create Promotion from array
     *
     * @param array $data
     * @return PromotionInterface
     */
    public static function createFromArray(array $data)
    {
        switch ($data['type']) {
            case ProductClassPricePercentPromotion::TYPE:
                $Promotion = new ProductClassPricePercentPromotion();
                break;
            default:
                throw new \InvalidArgumentException('$data[type] must be required');
        }

        if (isset($data['value'])) {
            $Promotion->setValue($data['value']);
        }

        return $Promotion;
    }
}
