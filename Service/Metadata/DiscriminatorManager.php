<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Service\Metadata;

use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;

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
     *
     * @return DiscriminatorInterface
     */
    public function create($discriminatorType)
    {
        switch ($discriminatorType) {
            case Operator\AllOperator::TYPE:
                $this->container[Operator\AllOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\AllOperator::TYPE)
                    ->setName('is all of')
                    ->setClass(Operator\AllOperator::class)
                    ->setDescription('');

                return $this->container[Operator\AllOperator::TYPE];

            case Operator\InOperator::TYPE:
                $this->container[Operator\InOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\InOperator::TYPE)
                    ->setName('is one of')
                    ->setClass(Operator\InOperator::class)
                    ->setDescription('');

                return $this->container[Operator\InOperator::TYPE];

            case Operator\EqualOperator::TYPE:
                $this->container[Operator\EqualOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\EqualOperator::TYPE)
                    ->setName('is equal to')
                    ->setClass(Operator\EqualOperator::class)
                    ->setDescription('');

                return $this->container[Operator\EqualOperator::TYPE];

            case Operator\NotEqualOperator::TYPE:
                $this->container[Operator\NotEqualOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\NotEqualOperator::TYPE)
                    ->setName('is not equal to')
                    ->setClass(Operator\NotEqualOperator::class)
                    ->setDescription('');

                return $this->container[Operator\NotEqualOperator::TYPE];

            case ProductClassRule::TYPE:
                $this->container[ProductClassRule::TYPE] = (new Discriminator())
                    ->setType(ProductClassRule::TYPE)
                    ->setName('Product Class Rule')
                    ->setClass(ProductClassRule::class)
                    ->setDescription('');

                return $this->container[ProductClassRule::TYPE];

            case ProductClassIdCondition::TYPE:
                $this->container[ProductClassIdCondition::TYPE] = (new Discriminator())
                    ->setType(ProductClassIdCondition::TYPE)
                    ->setName('Product Class Id Condition')
                    ->setClass(ProductClassIdCondition::class)
                    ->setDescription('');

                return $this->container[ProductClassIdCondition::TYPE];

            case ProductCategoryIdCondition::TYPE:
                $this->container[ProductCategoryIdCondition::TYPE] = (new Discriminator())
                    ->setType(ProductCategoryIdCondition::TYPE)
                    ->setName('Product Category Id Condition')
                    ->setClass(ProductCategoryIdCondition::class)
                    ->setDescription('');

                return $this->container[ProductCategoryIdCondition::TYPE];

            case ProductClassPricePercentPromotion::TYPE:
                $this->container[ProductClassPricePercentPromotion::TYPE] = (new Discriminator())
                    ->setType(ProductClassPricePercentPromotion::TYPE)
                    ->setName('Product Class Price Percent Promotion')
                    ->setClass(ProductClassPricePercentPromotion::class)
                    ->setDescription('');

                return $this->container[ProductClassPricePercentPromotion::TYPE];

            case ProductClassPriceAmountPromotion::TYPE:
                $this->container[ProductClassPriceAmountPromotion::TYPE] = (new Discriminator())
                    ->setType(ProductClassPriceAmountPromotion::TYPE)
                    ->setName('Product Class Price Amount Promotion')
                    ->setClass(ProductClassPriceAmountPromotion::class)
                    ->setDescription('');

                return $this->container[ProductClassPriceAmountPromotion::TYPE];

            default:
        }

        throw new \InvalidArgumentException('Unsupported '.$discriminatorType.' type');
    }

    /**
     * Get a discriminator
     *
     * @param $discriminatorType
     *
     * @return DiscriminatorInterface
     */
    public function get($discriminatorType)
    {
        return isset($this->container[$discriminatorType])
            ? $this->container[$discriminatorType]
            : $this->create($discriminatorType);
    }
}
