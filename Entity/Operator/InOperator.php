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

namespace Plugin\FlashSale\Entity\Operator;

use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\ConditionInterface;
use Plugin\FlashSale\Entity\OperatorInterface;

class InOperator implements OperatorInterface
{
    const TYPE = 'operator_in';

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
        if (!is_array($condition) && !$condition instanceof DoctrineCollection) {
            $condition = explode(',', $condition);
        }

        if (is_array($data)) {
            if (!$condition instanceof DoctrineCollection) {
                return (bool)array_intersect($condition, $data);
            }
        } else {
            foreach ($condition as $cond) {
                if ($cond instanceof ConditionInterface) {
                    $result = $cond->match($data);
                } else {
                    $result = ($cond == $data);
                }

                if ($result) {
                    return true;
                }
            }
        }

        return false;
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
                    $qb->andWhere($qb->expr()->in('pc.id', $condition->getValue()));
                }
                break;

            case EqualOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->andWhere($qb->expr()->in('pc.id', $condition->getValue()));
                }
                break;

            case InOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->orWhere($qb->expr()->in('pc.id', $condition->getValue()));
                }

                if ($condition instanceof Condition\ProductCategoryIdCondition) {
                    $qb->join('p.ProductCategories', 'pct');
                    $qb->orWhere($qb->expr()->in('pct.category_id', $condition->getValue()));
                }
                break;

                // Todo: I'm not sure
            case NotEqualOperator::TYPE:
                if ($condition instanceof Condition\ProductClassIdCondition) {
                    $qb->andWhere($qb->expr()->notIn('pc.id', $condition->getValue()));
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
