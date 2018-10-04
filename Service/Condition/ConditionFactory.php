<?php
namespace Plugin\FlashSale\Service\Condition;

use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;

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
            case ProductClassIdCondition::TYPE:
                $Condition = new ProductClassIdCondition();
                break;
            default:
                throw new \InvalidArgumentException('$data[type] must be required');
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
