<?php
namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Service\Common\IdentifierInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AmountPromotion extends Promotion implements PromotionInterface, IdentifierInterface
{
    const TYPE = 'amount';

    /**
     * {@inheritdoc}
     *
     * @param $value
     * @return mixed
     */
    public function getDiscountValue($value)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'Amount Promotion';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return [
            'percent',
            'amount'
        ];
    }
}
