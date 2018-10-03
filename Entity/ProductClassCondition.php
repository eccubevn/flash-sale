<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Operator\OperatorInterface;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductClassCondition extends Condition implements ConditionInterface
{
    const TYPE = 'condition_product_class';

    /**
     * {@inheritdoc}
     *
     * @param $data
     * @return bool
     */
    public function match($data)
    {
        if ($data instanceof ProductClass) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\NotEqualOperator::TYPE,
        ];
    }
}
