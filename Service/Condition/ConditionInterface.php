<?php
namespace Plugin\FlashSale\Service\Condition;

use Plugin\FlashSale\Service\Operator\OperatorInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;

interface ConditionInterface
{
    /**
     * Validate condition
     *
     * @param $data
     * @return bool
     */
    public function isValid($data);

    /**
     * Get allowed operator
     *
     * @return OperatorInterface[]|IdentifierInterface[]
     */
    public function getOperators() : array;

    /**
     * Get allowed attribute
     *
     * @return array
     */
    public function getAttributes();
}