<?php
namespace Plugin\FlashSale\Service\Rule\EventListener;

use Eccube\Event\TemplateEvent;

class ProductClassEventListener implements EventListenerInterface
{
    /**
     * Get subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            'Product/detail.twig' => 'onProductDetail'
        ];
    }

    /**
     * Display discount price at product detail
     *
     * @param TemplateEvent $event
     */
    public function onProductDetail(TemplateEvent $event): void
    {
        $event->addSnippet('@FlashSale/default/detail.twig');
    }
}
