<?php
namespace Plugin\FlashSale\Service\Rule\EventListener;

interface EventListenerInterface
{
    /**
     * Get subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents() : array ;
}
