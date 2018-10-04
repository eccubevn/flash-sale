<?php
namespace Plugin\FlashSale\Service\Rule;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Eccube\Event\TemplateEvent;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;
use Eccube\Common\EccubeConfig;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\FlashSale;



class ProductClassRuleEventSubscriber implements EventSubscriberInterface
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var \NumberFormatter
     */
    protected $formatter;

    /**
     * ProductClassRuleEventSubscriber constructor.
     * @param FlashSaleRepository $flashSaleRepository
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        FlashSaleRepository $flashSaleRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->flashSaleRepository = $flashSaleRepository;
        $this->formatter =  new \NumberFormatter($this->eccubeConfig['locale'], \NumberFormatter::CURRENCY);
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Product/detail.twig' => 'onTemplateProductDetail',
            'Product/list.twig' => 'onTemplateProductList',
        ];
    }

    /**
     * Change price of product depend on flash sale
     *
     * @param TemplateEvent $event
     */
    public function onTemplateProductDetail(TemplateEvent $event)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$event->hasParameter('Product') || !$FlashSale instanceof FlashSale) {
            return;
        }

        $json = [];

        /** @var RuleInterface $Rule */
        foreach ($FlashSale->getRules() as $Rule) {
            /** @var ProductClass $ProductClass */
            foreach ($event->getParameter('Product')->getProductClasses() as $ProductClass) {
                if ($Rule->match($ProductClass)) {
                    $discountItems = $Rule->getDiscountItems($ProductClass);
                    $discountItem = current($discountItems);
                    $discountPrice = -1 * $discountItem->getPrice();
                    $discountPercent = round($discountPrice*100/$ProductClass->getPrice02());
                    $json[$ProductClass->getId()] = [
                        'message' => '<p><span>'.$this->formatter->formatCurrency($ProductClass->getPrice02IncTax() - $discountPrice, $this->eccubeConfig['currency']).'</span> (-'.$discountPercent.'%)</p>'
                    ];
                }
            }
        }

        if (empty($json)) {
            return;
        }

        $event->setParameter('ProductFlashSale', json_encode($json));
        $event->addSnippet('@FlashSale/default/detail.twig');
    }

    /**
     * Change price of product depend on flash sale
     *
     * @param TemplateEvent $event
     */
    public function onTemplateProductList(TemplateEvent $event)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$event->hasParameter('pagination') || !$FlashSale instanceof FlashSale) {
            return;
        }

        $json = [];

        /** @var RuleInterface $Rule */
        foreach ($FlashSale->getRules() as $Rule) {
            /** @var Product $Product */
            foreach ($event->getParameter('pagination') as $Product) {
                /** @var ProductClass $ProductClass */
                foreach ($Product->getProductClasses() as $ProductClass) {
                    if ($Rule->match($ProductClass)) {
                        $discountItems = $Rule->getDiscountItems($ProductClass);
                        $discountItem = current($discountItems);
                        $discountPrice = -1 * $discountItem->getPrice();
                        $discountPercent = round($discountPrice*100/$ProductClass->getPrice02());
                        $json[$ProductClass->getId()] = [
                            'message' => '<p><span>'.$this->formatter->formatCurrency($ProductClass->getPrice02IncTax() - $discountPrice, $this->eccubeConfig['currency']).'</span> (-'.$discountPercent.'%)</p>'
                        ];
                    }
                }
            }
        }

        if (empty($json)) {
            return;
        }

        $event->setParameter('ProductFlashSale', json_encode($json));
        $event->addSnippet('@FlashSale/default/list.twig');
    }
}
