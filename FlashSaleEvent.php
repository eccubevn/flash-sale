<?php

namespace Plugin\FlashSale;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Event\TemplateEvent;
use Eccube\Twig\Extension\EccubeExtension;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FlashSaleEvent implements EventSubscriberInterface
{
    /** @var FlashSaleRepository */
    protected $flashSaleRepository;

    /** @var EccubeConfig */
    protected $eccubeConfig;

    /**
     * FlashSaleEvent constructor.
     *
     * @param FlashSaleRepository $flashSaleRepository
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(FlashSaleRepository $flashSaleRepository, EccubeConfig $eccubeConfig)
    {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'detail',
            'Product/list.twig' => 'list',
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

        $parameters = $event->getParameters();
        /** @var Product $Product */
        $Product = $parameters['Product'];
        $ProductClasses = $Product->getProductClasses();

        $json = [];
        $locale = $this->eccubeConfig['locale'];
        $currency = $this->eccubeConfig['currency'];
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        /** @var ProductClass $ProductClass */
        foreach ($ProductClasses as $ProductClass) {
            $per = 20;
            $json[$ProductClass->getId()] = [
                'message' => '<p><span>'.$formatter->formatCurrency($ProductClass->getPrice02IncTax() * ((100 - $per)/100), $currency).'</span> (-'.$per.'%)</p>'
            ];
        }

        $parameters['ProductFlashSale'] = json_encode($json);
        $event->setParameters($parameters);
    }

    /**
     * @param TemplateEvent $event
     */
    public function list(TemplateEvent $event)
    {
        $CurrentEvent = $this->flashSaleRepository->getCurrentEvent();
        if ($CurrentEvent) {
            $event->addSnippet('@FlashSale/default/list.twig');
        }

        $parameters = $event->getParameters();
        $pagination = $parameters['pagination'];
        $json = [];
        /** @var Product $Product */
        foreach ($pagination->getItems() as $Product){
            $ProductClasses = $Product->getProductClasses();
            $locale = $this->eccubeConfig['locale'];
            $currency = $this->eccubeConfig['currency'];
            $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
            /** @var ProductClass $ProductClass */
            foreach ($ProductClasses as $ProductClass) {
                $per = 20;
                $json[$ProductClass->getId()] = [
                    'message' => '<p><span>'.$formatter->formatCurrency($ProductClass->getPrice02IncTax() * ((100 - $per)/100), $currency).'</span> (-'.$per.'%)</p>'
                ];
            }
        }

        $parameters['ProductFlashSale'] = json_encode($json);
        $event->setParameters($parameters);
    }
}
