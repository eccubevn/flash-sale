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

namespace Plugin\FlashSale\Service\Event;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events as DoctrineEvents;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Plugin\FlashSale\Entity\PromotionInterface;
use Plugin\FlashSale\Entity\RuleInterface;
use Plugin\FlashSale\Entity\ConditionInterface;
use Plugin\FlashSale\Entity\OperatorInterface;
use Plugin\FlashSale\Factory\OperatorFactory;
use Plugin\FlashSale\Factory\ConditionFactory;
use Plugin\FlashSale\Factory\PromotionFactory;
use Plugin\FlashSale\Factory\RuleFactory;
use Plugin\FlashSale\Repository\DiscriminatorRepository;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Utils\Memoization;

class EntityDiEventSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $container;

    /**
     * EntityDiEventSubscriber constructor.
     *
     * @param Memoization $memoization
     * @param ConditionFactory $conditionFactory
     * @param PromotionFactory $promotionFactory
     * @param RuleFactory $ruleFactory
     * @param OperatorFactory $operatorFactory
     * @param EntityManagerInterface $entityManager
     * @param DiscriminatorRepository $discriminatorRepository
     */
    public function __construct(
        Memoization $memoization,
        ConditionFactory $conditionFactory,
        PromotionFactory $promotionFactory,
        RuleFactory $ruleFactory,
        OperatorFactory $operatorFactory,
        EntityManagerInterface $entityManager,
        DiscriminatorRepository $discriminatorRepository
    ) {
        $this->defineDI($memoization);
        $this->defineDI($conditionFactory);
        $this->defineDI($promotionFactory);
        $this->defineDI($ruleFactory);
        $this->defineDI($operatorFactory);
        $this->defineDI($discriminatorRepository);
        $this->container[EntityManagerInterface::class] = $entityManager;
    }

    /**
     * Add dependencies into container
     *
     * @param $obj
     */
    protected function defineDI($obj)
    {
        $this->container[get_class($obj)] = $obj;
    }

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            DoctrineEvents::postLoad,
        ];
    }

    /**
     * Inject dependencies into entity
     *
     * @param LifecycleEventArgs $eventArgs
     *
     * @throws \ReflectionException
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof RuleInterface
            && !$entity instanceof ConditionInterface
            && !$entity instanceof OperatorInterface
            && !$entity instanceof PromotionInterface
            && !$entity instanceof FlashSale
        ) {
            return;
        }

        $refClass = new \ReflectionClass($entity);
        foreach ($refClass->getMethods(\ReflectionMethod::IS_PUBLIC) as $refMethod) {
            if (false !== stripos($refMethod->getDocComment(), '@required') && preg_match('#(?:^/\*\*|\n\s*+\*)\s*+@required(?:\s|\*/$)#i', $refMethod->getDocComment())) {
                foreach ($refMethod->getParameters() as $refParam) {
                    if (isset($this->container[$refParam->getType()->getName()])) {
                        $entity->{$refMethod->getName()}($this->container[$refParam->getType()->getName()]);
                    }
                }
            }
        }
    }
}
