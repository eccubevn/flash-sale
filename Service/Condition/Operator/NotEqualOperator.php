<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

class NotEqualOperator implements OperatorInterface
{
    const TYPE = 'not_equal';

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function isValid($condition, $data)
    {
        return $condition != $data;
    }
}
