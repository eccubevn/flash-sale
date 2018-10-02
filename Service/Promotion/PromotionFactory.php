<?php
namespace Plugin\FlashSale\Service\Promotion;

use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\AmountPromotion;

class PromotionFactory
{
    /**
     * Create Promotion from array
     *
     * @param array $data
     * @return Rule
     */
    public static function createFromArray(array $data)
    {
        switch ($data['type']) {
            case AmountPromotion::TYPE:
                $Promotion = new AmountPromotion();
                break;
            default:
                $Promotion = new Rule();
        }
        if (isset($data['attribute'])) {
            $Promotion->setAttribute($data['attribute']);
        }
        if (isset($data['value'])) {
            $Promotion->setValue($data['value']);
        }

        return $Promotion;
    }
}
