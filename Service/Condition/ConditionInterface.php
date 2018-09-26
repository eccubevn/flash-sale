<?php
namespace Plugin\FlashSale\Service\Condition;

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
     * @return array
     */
    public function getOperators();

    /**
     * Get allowed attribute
     *
     * @return array
     */
    public function getAttributes();
}