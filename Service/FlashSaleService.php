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

namespace Plugin\FlashSale\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Annotations\AnnotationReader;
use Eccube\Entity\Category;
use Eccube\Entity\ClassCategory;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\ProductClassRepository;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Condition\ConditionInterface;

class FlashSaleService
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @var DiscriminatorManager
     */
    protected $discriminatorManager;

    /** @var EntityManagerInterface */
    protected $entityManager;

    /**
     * FlashSaleService constructor.
     *
     * @param AnnotationReader $annotationReader
     * @param DiscriminatorManager $discriminatorManager
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        AnnotationReader $annotationReader,
        DiscriminatorManager $discriminatorManager,
        EntityManagerInterface $entityManager
    ) {
        $this->annotationReader = $annotationReader;
        $this->discriminatorManager = $discriminatorManager;
        $this->entityManager = $entityManager;
    }


    /**
     * Get metadata
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function getMetadata()
    {
        $refClassRule = new \ReflectionClass(Rule::class);
        $annotation = $this->annotationReader->getClassAnnotation($refClassRule, DiscriminatorMap::class);
        $result = [];
        foreach ($annotation->value as $ruleType => $ruleClass) {
            $discriminator = $this->discriminatorManager->get($ruleType);
            $result['rule_types'][$ruleType] = [
                'name' => $discriminator->getName(),
                'description' => $discriminator->getDescription(),
                'operator_types' => [],
                'condition_types' => [],
                'promotion_types' => [],
            ];
            /** @var RuleInterface $ruleEntity */
            $ruleClass = $discriminator->getClass();
            $ruleEntity = new $ruleClass();
            foreach ($ruleEntity->getOperatorTypes() as $operatorType) {
                $discriminator = $this->discriminatorManager->get($operatorType);
                $result['rule_types'][$ruleType]['operator_types'][$operatorType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                ];
            }

            foreach ($ruleEntity->getConditionTypes() as $conditionType) {
                $discriminator = $this->discriminatorManager->get($conditionType);
                $result['rule_types'][$ruleType]['condition_types'][$conditionType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                    'operator_types' => [],
                ];
                /** @var ConditionInterface $conditionEntity */
                $conditionClass = $discriminator->getClass();
                $conditionEntity = new $conditionClass();
                foreach ($conditionEntity->getOperatorTypes() as $operatorType) {
                    $discriminator = $this->discriminatorManager->get($operatorType);
                    $result['rule_types'][$ruleType]['condition_types'][$conditionType]['operator_types'][$operatorType] = [
                        'name' => $discriminator->getName(),
                        'description' => $discriminator->getDescription(),
                    ];
                }
            }

            foreach ($ruleEntity->getPromotionTypes() as $promotionType) {
                $discriminator = $this->discriminatorManager->get($promotionType);
                $result['rule_types'][$ruleType]['promotion_types'][$promotionType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                ];
            }
        }

        return $result;
    }

    /**
     * @param $categoryIds
     * @return mixed
     */
    public function getCategoryName($categoryIds){
        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('c.id, c.name')
            ->from(Category::class, 'c')
            ->where($qb->expr()->in('c.id', ':ids'))
            ->setParameter('ids', $categoryIds);
        return $qb->getQuery()->getResult();
    }

    /**
     * @param $productClassIds
     * @return mixed
     */
    public function getProductClassName($productClassIds){
        /** @var ProductClassRepository $ProductClass */
        $ProductClass = $this->entityManager->getRepository(ProductClass::class);
        $qb = $ProductClass->createQueryBuilder('pc');
        $qb->select("pc.id as id, p.name as name, CONCAT(pc1.name, ' - ',pc2.name) AS class_name")
            ->innerJoin('pc.Product', 'p')
            ->innerJoin('pc.ClassCategory1', 'pc1')
            ->innerJoin('pc.ClassCategory2', 'pc2')
            ->where($qb->expr()->in('pc.id', ':ids'))
            ->setParameter('ids', $productClassIds);
        return $qb->getQuery()->getResult();
    }
}
