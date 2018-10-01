<?php
namespace Plugin\FlashSale\Service\Operator;

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;

interface OperatorInterface
{
    /**
     * Implement validate logic
     *
     * @param $condition
     * @param $data
     * @return bool
     */
    public function isValid($condition, $data);

    /**
     * @param QueryBuilder $qb
     * @param Condition $condition
     * @return QueryBuilder
     */
    public function parseCondition(QueryBuilder $qb, Condition $condition);
}
