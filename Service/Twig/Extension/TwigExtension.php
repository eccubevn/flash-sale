<?php
namespace Plugin\FlashSale\Service\Twig\Extension;

use Eccube\Entity\CartItem;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Cart;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\RuleInterface;


class TwigExtension extends \Twig_Extension
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
     * TwigExtension constructor.
     * @param FlashSaleRepository $flashSaleRepository
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        FlashSaleRepository $flashSaleRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->flashSaleRepository = $flashSaleRepository;
        $this->formatter = new \NumberFormatter($this->eccubeConfig['locale'], \NumberFormatter::CURRENCY);
    }

    /**
     * {@inheritdoc}
     *
     * @return \Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('flashSalePrice', [$this, 'getFlashSalePrice'])
        ];
    }

    /**
     * Get flashSale price
     *
     * @param $object
     * @param $default
     *
     * @return string
     */
    public function getFlashSalePrice($object, $default)
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        if (!$FlashSale) {
            return $this->formatter->formatCurrency($default, $this->eccubeConfig['currency']);
        }

        if ($object instanceof CartItem) {
            $ProductClass = $object->getProductClass();
            $price = $ProductClass->getPrice02IncTax();

            /** @var RuleInterface $Rule */
            foreach ($FlashSale->getRules() as $Rule) {
                if (!$Rule->match($ProductClass)) {
                    continue;
                }
                foreach ($Rule->getDiscountItems($ProductClass) as $discountItem) {
                    $price += $discountItem->getPrice();
                }
            }

            if ($price != $default) {
                $percent = 100 - floor($price * 100 / $default);
                $price = $this->formatter->formatCurrency($price, $this->eccubeConfig['currency']);
                $default = $this->formatter->formatCurrency($default, $this->eccubeConfig['currency']);

                return "<del>{$default}</del><span class='ec-color-red'>{$price} (-{$percent}%)</span>";
            }
        } elseif ($object instanceof OrderItem) {
            $ProductClass = $object->getProductClass();
            $price = $ProductClass->getPrice02IncTax();

            /** @var RuleInterface $Rule */
            foreach ($FlashSale->getRules() as $Rule) {
                if (!$Rule->match($ProductClass)) {
                    continue;
                }
                foreach ($Rule->getDiscountItems($ProductClass) as $discountItem) {
                    $price += $discountItem->getPrice();
                }
            }

            if ($price != $default) {
                $percent = 100 - floor($price * 100 / $default);
                $price = $this->formatter->formatCurrency($price, $this->eccubeConfig['currency']);
                $default = $this->formatter->formatCurrency($default, $this->eccubeConfig['currency']);

                return "<del>{$default}</del><span class='ec-color-red'>{$price} (-{$percent}%)</span>";
            }
        } elseif ($object instanceof Cart) {
            $price = $object->getTotal();

            /** @var RuleInterface $Rule */
            foreach ($FlashSale->getRules() as $Rule) {
                foreach ($Rule->getDiscountItems($object) as $discountItem) {
                    $price += $discountItem->getPrice();
                }
            }

            if ($price != $default) {
                $percent = 100 - floor($price * 100 / $default);
                $price = $this->formatter->formatCurrency($price, $this->eccubeConfig['currency']);
                $default = $this->formatter->formatCurrency($default, $this->eccubeConfig['currency']);

                return "<del>{$default}</del><span class='ec-color-red'>{$price} (-{$percent}%)</span>";
            }
        }

        return $this->formatter->formatCurrency($default, $this->eccubeConfig['currency']);
    }
}
