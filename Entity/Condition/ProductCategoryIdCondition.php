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

namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\QueryBuilder;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Service\Operator\OperatorInterface;

/**
 * @ORM\Entity
 */
class ProductCategoryIdCondition extends Condition
{
    const TYPE = 'condition_product_category_id';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     *
     * @return $this
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($ProductClass)
    {
        /** @var Product $ProductClass */
        if (!$ProductClass instanceof ProductClass) {
            return false;
        }

        $cateIds = [];
        foreach ($ProductClass->getProduct()->getProductCategories() as $category) {
            $cateIds[] = $category->getCategoryId();
        }

        return $this->getOperatorFactory()->createByType($this->getOperator())->match($this->value, $cateIds);
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

        $queryBuilder->join('p.ProductCategories', 'pct');

        // rule check
        switch ($operatorRule->getType()) {
            case Operator\InOperator::TYPE:
                $this->createInRule($queryBuilder, $operatorCondition);
                break;

            case Operator\AllOperator::TYPE:
                $this->createAllRule($queryBuilder, $operatorCondition);
                break;
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
            Operator\InOperator::TYPE,
            Operator\NotEqualOperator::TYPE,
        ];
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param OperatorInterface $operatorCondition
     */
    private function createAllRule(QueryBuilder $queryBuilder, OperatorInterface $operatorCondition): void
    {
        switch ($operatorCondition->getType()) {
            case Operator\InOperator::TYPE:
                $queryBuilder->andWhere($queryBuilder->expr()->in('pct.category_id', $this->getValue()));
                break;

            case Operator\NotEqualOperator::TYPE:
                // get product in category
                $productRepo = $this->getEntityManager()->getRepository(Product::class);
                $qb2 = $productRepo->createQueryBuilder('p2');
                $qb2->where($qb2->expr()->in('pct', $this->getValue()));

                // not in that product
                $queryBuilder->andWhere($queryBuilder->expr()->notIn('p.id', $qb2->getDQL()));
                break;
        }
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @param OperatorInterface $operatorCondition
     */
    private function createInRule(QueryBuilder $queryBuilder, OperatorInterface $operatorCondition): void
    {
        switch ($operatorCondition->getType()) {
            case Operator\InOperator::TYPE:
                $queryBuilder->orWhere($queryBuilder->expr()->in('pct.category_id', $this->getValue()));
                break;

            case Operator\NotEqualOperator::TYPE:
                // get product in category
                $productRepo = $this->getEntityManager()->getRepository(Product::class);
                $qb2 = $productRepo->createQueryBuilder('p2');
                $qb2->where($qb2->expr()->in('pct', $this->getValue()));

                // not in that product
                $queryBuilder->orWhere($queryBuilder->expr()->notIn('p.id', $qb2->getDQL()));
                break;
        }
    }
}
