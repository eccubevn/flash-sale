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

use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\Common\Annotations\AnnotationReader;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\RuleInterface;
use Plugin\FlashSale\Entity\ConditionInterface;
use Plugin\FlashSale\Repository\DiscriminatorRepository;

class FlashSaleService
{
    /**
     * @var AnnotationReader
     */
    protected $annotationReader;

    /**
     * @var DiscriminatorRepository
     */
    protected $discriminatorRepository;

    /**
     * FlashSaleService constructor.
     *
     * @param AnnotationReader $annotationReader
     * @param DiscriminatorRepository $discriminatorRepository
     */
    public function __construct(
        AnnotationReader $annotationReader,
        DiscriminatorRepository $discriminatorRepository
    ) {
        $this->annotationReader = $annotationReader;
        $this->discriminatorRepository = $discriminatorRepository;
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
            $discriminator = $this->discriminatorRepository->find($ruleType);
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
                $discriminator = $this->discriminatorRepository->find($operatorType);
                $result['rule_types'][$ruleType]['operator_types'][$operatorType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                ];
            }

            foreach ($ruleEntity->getConditionTypes() as $conditionType) {
                $discriminator = $this->discriminatorRepository->find($conditionType);
                $result['rule_types'][$ruleType]['condition_types'][$conditionType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                    'operator_types' => [],
                ];
                /** @var ConditionInterface $conditionEntity */
                $conditionClass = $discriminator->getClass();
                $conditionEntity = new $conditionClass();
                foreach ($conditionEntity->getOperatorTypes() as $operatorType) {
                    $discriminator = $this->discriminatorRepository->find($operatorType);
                    $result['rule_types'][$ruleType]['condition_types'][$conditionType]['operator_types'][$operatorType] = [
                        'name' => $discriminator->getName(),
                        'description' => $discriminator->getDescription(),
                    ];
                }
            }

            foreach ($ruleEntity->getPromotionTypes() as $promotionType) {
                $discriminator = $this->discriminatorRepository->find($promotionType);
                $result['rule_types'][$ruleType]['promotion_types'][$promotionType] = [
                    'name' => $discriminator->getName(),
                    'description' => $discriminator->getDescription(),
                ];
            }
        }

        return $result;
    }
}
