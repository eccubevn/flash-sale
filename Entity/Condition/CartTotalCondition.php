<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Condition;
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
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
     * @param Operator\OperatorFactory $operatorFactory
     *
     * @return $this
     * @required
     */
    public function setOperatorFactory(Operator\OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;

        return $this;
    }

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
            return $this->operatorFactory->createByType($this->getOperator())->match($this->value, $data->getSubtotal());
        }

        return false;
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param OperatorInterface $operatorRule
     * @param OperatorInterface $operatorCondition
     *
     * @return QueryBuilder
     */
    public function createQueryBuilder(QueryBuilder $queryBuilder, OperatorInterface $operatorRule, OperatorInterface $operatorCondition): QueryBuilder
    {
        // Check is support
        if (!in_array($operatorCondition->getType(), $this->getOperatorTypes())) {
            return $queryBuilder;
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
