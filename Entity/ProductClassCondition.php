<?php
namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Condition\Operator\InOperator;
use Plugin\FlashSale\Service\Condition\Operator\AllOperator;
use Plugin\FlashSale\Service\Condition\Operator\EqualOperator;
use Plugin\FlashSale\Service\Condition\Operator\NotEqualOperator;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class ProductClassCondition extends Condition implements ConditionInterface
{
    /**
     * {@inheritdoc}
     *
     * @param $data
     * @return bool
     */
    public function isValid($data)
    {
        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAttributes()
    {
        return [
            'id'
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperators()
    {
        return [
            InOperator::TYPE,
            AllOperator::TYPE,
            EqualOperator::TYPE,
            NotEqualOperator::TYPE
        ];
    }
}
