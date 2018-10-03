<?php
namespace Plugin\FlashSale;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Eccube\Event\TemplateEvent;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Rule\EventListener\EventListenerInterface;
use Plugin\FlashSale\Entity\FlashSale;

class FlashSaleEvent implements EventSubscriberInterface
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * FlashSaleEvent constructor.
     *
     * @param FlashSaleRepository $flashSaleRepository
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        FlashSaleRepository $flashSaleRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->eventDispatcher = $eventDispatcher;
    }


    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'subscribeEvents',
//            'Product/detail.twig' => 'detail',
        ];
    }

    public function subscribeEvents()
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale instanceof FlashSale) {
            return;
        }
        /** @var RuleInterface $Rule */
        foreach ($FlashSale->getRules() as $Rule) {
            if (!$Rule->getEventListener() instanceof  EventListenerInterface) {
                continue;
            }
            foreach ($Rule->getEventListener()->getSubscribedEvents() as $event => $callback) {
                $this->eventDispatcher->addListener($event, [$Rule->getEventListener(), $callback]);
            }
        }
    }
}
