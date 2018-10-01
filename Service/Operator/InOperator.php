<?php
namespace Plugin\FlashSale\Service\Operator;

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;

class InOperator implements OperatorInterface, IdentifierInterface
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
                $qb->orWhere($qb->expr()->in('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;

                // Todo: I'm not sure
            case NotEqualOperator::TYPE:
                $qb->andWhere($qb->expr()->notIn('pc.'.$condition->getAttribute(), $condition->getValue()));
                break;
            default:
            break;
        }

        return $qb;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'is one of';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
