<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

class OperatorFactory
{
    /**
     *
     * Create operator
     *
     * @param $type
     * @return OperatorInterface
     */
    public function createByType($type)
    {
        switch ($type) {
            case AllOperator::TYPE:
                return new AllOperator();

            case EqualOperator::TYPE:
                return new EqualOperator();

            case InOperator::TYPE:
                return new InOperator();

            case NotEqualOperator::TYPE;
                return new NotEqualOperator();
        }

        throw new \InvalidArgumentException('Not found operator have type ' . $type);
    }
}
