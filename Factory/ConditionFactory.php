<?php
namespace Plugin\FlashSale\Factory;

use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\ConditionInterface;

class ConditionFactory
{
    /**
     * Create condition
     *
     * @param array $data
     * @return ConditionInterface
     */
    public function create(array $data = [])
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($data['type']) {
            case Condition\ProductClassIdCondition::TYPE:
                $Condition = new Condition\ProductClassIdCondition();
                break;
            case Condition\ProductCategoryIdCondition::TYPE:
                $Condition = new Condition\ProductCategoryIdCondition();
                break;
            case Condition\CartTotalCondition::TYPE:
                $Condition = new Condition\CartTotalCondition();
                break;
            default:
                throw new \InvalidArgumentException($data['type'].' unsupported');
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
