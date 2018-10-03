<?php
namespace Plugin\FlashSale\Service\Rule;

use Plugin\FlashSale\Service\Rule\EventListener\EventListenerInterface;

interface RuleInterface
{
    /**
     * Get operator types
     *
     * @return array
     */
    public function getOperatorTypes(): array;

    /**
     * Get condition types
     *
     * @return array
     */
    public function getConditionTypes(): array;

    /**
     * Get promotion types
     *
     * @return array
     */
    public function getPromotionTypes(): array;

    /**
     * @return EventListenerInterface
     */
    public function getEventListener(): EventListenerInterface;

    /**
     * Check a object match conditions of rule
     *
     * @param $object
     * @return bool
     */
    public function match($object): bool ;

    /**
     * Get discount item
     *
     * @param $object
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($object): array ;
}
