<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

interface OperatorInterface
{
    /**
     * Implement validate logic
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function isValid($condition, $data);
}
