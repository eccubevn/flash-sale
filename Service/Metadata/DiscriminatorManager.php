<?php
namespace Plugin\FlashSale\Service\Metadata;

use Plugin\FlashSale\Entity\ProductClassRule;
use Plugin\FlashSale\Entity\ProductClassCondition;
use Plugin\FlashSale\Entity\AmountPromotion;
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

            case ProductClassCondition::TYPE:
                $this->container[ProductClassCondition::TYPE] = new Discriminator(
                    ProductClassCondition::TYPE,
                    'Product Class Condition',
                    ProductClassCondition::class,
                    ''
                );
                return $this->container[ProductClassCondition::TYPE];

            case AmountPromotion::TYPE:
                $this->container[AmountPromotion::TYPE] = new Discriminator(
                    AmountPromotion::TYPE,
                    'Amount Promotion',
                    AmountPromotion::class,
                    ''
                );
                return $this->container[AmountPromotion::TYPE];

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