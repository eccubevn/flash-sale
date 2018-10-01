<?php
namespace Plugin\FlashSale\Service\Condition\Operator;

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Condition\ConditionInterface;

class AllOperator implements OperatorInterface
{
    const TYPE = 'all';

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

            if (!$result) {
                return false;
            }
        }

        return true;
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
                $qb->andWhere($qb->expr()->in('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

            case EqualOperator::TYPE:
                $qb->andWhere($qb->expr()->in('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

            case InOperator::TYPE:
                $qb->andWhere($qb->expr()->in('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

            case NotEqualOperator::TYPE:
                $qb->andWhere($qb->expr()->neq('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;
            default:
                break;
        }

        return $qb;
    }
}
