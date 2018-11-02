<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Exception\ConditionException;
use Plugin\FlashSale\Service\Operator;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Operator\OperatorInterface;

/**
 * @ORM\Entity()
 */
class CartTotalCondition extends Condition implements ConditionInterface
{
    const TYPE = 'condition_cart_total';

    /**
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($data)
    {
        if ($data instanceof Order) {
            $subtotal = $data->getSubtotal();
            foreach ($data->getOrderItems() as $OrderItem) {
                $subtotal -= $OrderItem->getFlashSaleTotalDiscount();
            }
            return $this->getOperatorFactory()->createByType($this->getOperator())->match($this->value, $subtotal);
        }

        return false;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param OperatorInterface $operatorRule
     * @param OperatorInterface $operatorCondition
     * @return QueryBuilder
     * @throws ConditionException
     */
    public function createQueryBuilder(QueryBuilder $queryBuilder, OperatorInterface $operatorRule, OperatorInterface $operatorCondition): QueryBuilder
    {
        // Check is support
        if (!in_array($operatorCondition->getType(), $this->getOperatorTypes())) {
            throw new ConditionException(trans('flash_sale.condition.exception', ['%operator%' => $operatorCondition->getType()]));
        }

        return $queryBuilder;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\GreaterThanOperator::TYPE,
            Operator\EqualOperator::TYPE,
            Operator\LessThanOperator::TYPE,
        ];
    }
}
