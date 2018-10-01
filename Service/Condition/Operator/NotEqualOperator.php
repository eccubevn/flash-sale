<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;

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

    /**
     * @param QueryBuilder $qb
     * @param Condition $condition
     * @return QueryBuilder
     */
    public function parseCondition(QueryBuilder $qb, Condition $condition)
    {
        $rule = $condition->getRule();
        switch ($rule->getOperator()) {
            case AllOperator::TYPE:
                $qb->andWhere($qb->expr()->neq('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

            case EqualOperator::TYPE:
                $qb->andWhere($qb->expr()->neq('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

            case InOperator::TYPE:
                $qb->orWhere($qb->expr()->neq('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

                // Todo: confuse
            case NotEqualOperator::TYPE:
                $qb->andWhere($qb->expr()->neq('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;
            default:
                break;
        }

        return $qb;
    }
}
