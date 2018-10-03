<?php
namespace Plugin\FlashSale\Service\Metadata;

use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Service\Operator as Operator;

class DiscriminatorManager
{
    /**
     * @var array
     */
    protected $container;

    /**
     * Create a discriminator
     *
     * @param $discriminatorType
     * @return DiscriminatorInterface
     */
    public function create($discriminatorType)
    {
        switch ($discriminatorType) {
            case Operator\AllOperator::TYPE:
                $this->container[Operator\AllOperator::TYPE] = new Discriminator(
                    Operator\AllOperator::TYPE,
                    'is all of',
                    Operator\AllOperator::class,
                    ''
                );
                return $this->container[Operator\AllOperator::TYPE];

            case Operator\InOperator::TYPE:
                $this->container[Operator\InOperator::TYPE] = new Discriminator(
                    Operator\InOperator::TYPE,
                    'is one of',
                    Operator\InOperator::class,
                    ''
                );
                return $this->container[Operator\InOperator::TYPE];

            case Operator\EqualOperator::TYPE:
                $this->container[Operator\EqualOperator::TYPE] = new Discriminator(
                    Operator\EqualOperator::TYPE,
                    'is equal to',
                    Operator\EqualOperator::class,
                    ''
                );
                return $this->container[Operator\EqualOperator::TYPE];

            case Operator\NotEqualOperator::TYPE:
                $this->container[Operator\NotEqualOperator::TYPE] = new Discriminator(
                    Operator\NotEqualOperator::TYPE,
                    'is not equal to',
                    Operator\NotEqualOperator::class,
                    ''
                );
                return $this->container[Operator\NotEqualOperator::TYPE];

            case ProductClassRule::TYPE:
                $this->container[ProductClassRule::TYPE] = new Discriminator(
                    ProductClassRule::TYPE,
                    'Product Class Rule',
                    ProductClassRule::class,
                    ''
                );
                return $this->container[ProductClassRule::TYPE];

            case ProductClassIdCondition::TYPE:
                $this->container[ProductClassIdCondition::TYPE] = new Discriminator(
                    ProductClassIdCondition::TYPE,
                    'Product Class Id Condition',
                    ProductClassIdCondition::class,
                    ''
                );
                return $this->container[ProductClassIdCondition::TYPE];

            case ProductClassPricePercentPromotion::TYPE:
                $this->container[ProductClassPricePercentPromotion::TYPE] = new Discriminator(
                    ProductClassPricePercentPromotion::TYPE,
                    'Amount Promotion',
                    ProductClassPricePercentPromotion::class,
                    ''
                );
                return $this->container[ProductClassPricePercentPromotion::TYPE];

            default:
        }

        throw new \InvalidArgumentException('Unsupported ' . $discriminatorType . ' type');
    }

    /**
     * Get a discriminator
     *
     * @param $discriminatorType
     * @return DiscriminatorInterface
     */
    public function get($discriminatorType)
    {
        return isset($this->container[$discriminatorType])
            ? $this->container[$discriminatorType]
            : $this->create($discriminatorType);
    }
}