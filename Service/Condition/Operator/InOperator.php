<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

use Plugin\FlashSale\Service\Condition\ConditionInterface;

class InOperator implements OperatorInterface
{
    const TYPE = 'in';

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function isValid($condition, $data)
    {
        foreach ($condition as $cond) {
            if ($cond instanceof ConditionInterface) {
                $result = $cond->isValid($data);
            } else {
                $result = ($cond == $data);
            }

            if ($result) {
                return true;
            }
        }

        return false;
    }
}
