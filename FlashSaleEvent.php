<?php

namespace Plugin\FlashSale;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FlashSaleEvent implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [];
    }
}
