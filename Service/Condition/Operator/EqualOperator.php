<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

class EqualOperator implements OperatorInterface
{
    const TYPE = 'equal';

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function isValid($condition, $data)
    {
        return $condition == $data;
    }
}