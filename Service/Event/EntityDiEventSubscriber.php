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
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Factory\ConditionFactory;
use Plugin\FlashSale\Factory\RuleFactory;
use Plugin\FlashSale\Factory\PromotionFactory;

class EntityDiEventSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    protected $container;

    /**
     * EntityDiEventSubscriber constructor.
     *
     * @param PromotionFactory $promotionFactory
     * @param RuleFactory $ruleFactory
     * @param ConditionFactory $conditionFactory
     * @param OperatorFactory $operatorFactory
     * @param EntityManagerInterface $entityManager
     * @param DiscriminatorManager $discriminatorManager
     */
    public function __construct(
        PromotionFactory $promotionFactory,
        RuleFactory $ruleFactory,
        ConditionFactory $conditionFactory,
        OperatorFactory $operatorFactory,
        EntityManagerInterface $entityManager,
        DiscriminatorManager $discriminatorManager
    ) {
        $this->defineDI($promotionFactory);
        $this->defineDI($ruleFactory);
        $this->defineDI($conditionFactory);
        $this->defineDI($operatorFactory);
        $this->defineDI($discriminatorManager);
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
