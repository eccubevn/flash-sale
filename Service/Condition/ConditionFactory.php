<?php
namespace Plugin\FlashSale\Service\Condition;

use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\ProductClassCondition;

class ConditionFactory
{
    /**
     * Create Condition from array
     *
     * @param array $data
     * @return Condition
     */
    public static function createFromArray(array $data)
    {
        switch ($data['type']) {
            case ProductClassCondition::TYPE:
                $Condition = new ProductClassCondition();
                break;
            default:
                $Condition = new Condition();
        }
        if (isset($data['attribute'])) {
            $Condition->setAttribute($data['attribute']);
        }
        if (isset($data['value'])) {
            $Condition->setValue($data['value']);
        }
        if (isset($data['operator'])) {
            $Condition->setOperator($data['operator']);
        }

        return $Condition;
    }
}
