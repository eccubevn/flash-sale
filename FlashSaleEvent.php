<?php

namespace Plugin\FlashSale;

use Eccube\Event\TemplateEvent;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FlashSaleEvent implements EventSubscriberInterface
{
    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /**
     * FlashSaleEvent constructor.
     *
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(FlashSaleRepository $flashSaleRepository)
    {
        $this->flashSaleRepository = $flashSaleRepository;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'detail',
        ];
    }

    /**
     * @param TemplateEvent $event
     */
    public function detail(TemplateEvent $event)
    {
        $CurrentEvent = $this->flashSaleRepository->getCurrentEvent();
        if ($CurrentEvent) {
            $event->addSnippet('@FlashSale/default/detail.twig');
        }

        /*
        $parameters = $event->getParameters();
        $parameters['ProductReviews'] = $ProductReviews;
        $parameters['ProductReviewAvg'] = $avg;
        $parameters['ProductReviewCount'] = $count;
        $event->setParameters($parameters);*/
    }
}
