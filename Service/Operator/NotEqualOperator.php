<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Service\Operator;

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;

class NotEqualOperator implements OperatorInterface
{
    const TYPE = 'operator_not_equal';

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     *
     * @return bool
     */
    public function match($condition, $data)
    {
        return $condition != $data;
    }

    /**
     * @param QueryBuilder $qb
     * @param Condition $condition
     *
     * @return QueryBuilder
     */
    public function parseCondition(QueryBuilder $qb, Condition $condition)
    {
        $rule = $condition->getRule();
        switch ($rule->getOperator()) {
            case AllOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->andWhere($qb->expr()->neq('pc.id', $condition->getValue()));
                }
                break;

            case EqualOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->andWhere($qb->expr()->neq('pc.id', $condition->getValue()));
                }
                break;

            case InOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->orWhere($qb->expr()->neq('pc.id', $condition->getValue()));
                }
                break;

                // Todo: confuse
            case NotEqualOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->andWhere($qb->expr()->neq('pc.id', $condition->getValue()));
                }
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
        return 'is not equal to';
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
