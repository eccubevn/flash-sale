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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events as DoctrineEvents;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\Common\EventSubscriber;
use Eccube\Entity\CartItem;
use Eccube\Entity\OrderItem;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\FlashSale;

class ProductClassRuleDoctrineEventSubscriber implements EventSubscriber
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * CartRuleEventSubscriber constructor.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     *
     * @return array|string[]
     */
    public function getSubscribedEvents()
    {
        return [
            DoctrineEvents::postLoad
        ];
    }

    /**
     * Calc flash sale discount & add to entity
     *
     * @param LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof ProductClass) {
            return;
        }

        $repository = $this->entityManager->getRepository(FlashSale::class);
        $FlashSale = $repository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }

        $discount = $FlashSale->getDiscount($entity);
        if (!$discount->getValue()) {
            return;
        }

        $entity->cleanFlashSaleDiscount()->addFlashSaleDiscount($discount->getRuleId(), $discount->getValue());
    }
}
