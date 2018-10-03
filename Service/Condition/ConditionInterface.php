<?php
namespace Plugin\FlashSale\Service\Condition;

use Plugin\FlashSale\Service\Operator\OperatorInterface;

interface ConditionInterface
{
    /**
     * Validate condition
     *
     * @param $data
     * @return bool
     */
    public function match($data);

    /**
     * Get allowed operator
     *
     * @return array
     */
    public function getOperatorTypes() : array;
}