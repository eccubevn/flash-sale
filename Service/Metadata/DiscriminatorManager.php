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
use Plugin\FlashSale\Entity\Rule\CartRule;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;
use Plugin\FlashSale\Entity\Promotion\CartTotalPercentPromotion;
use Plugin\FlashSale\Entity\Promotion\CartTotalAmountPromotion;

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

            case Operator\GreaterThanOperator::TYPE:
                $this->container[Operator\GreaterThanOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\GreaterThanOperator::TYPE)
                    ->setName('is greater than to')
                    ->setClass(Operator\GreaterThanOperator::class)
                    ->setDescription('');

                return $this->container[Operator\GreaterThanOperator::TYPE];

            case Operator\LessThanOperator::TYPE:
                $this->container[Operator\LessThanOperator::TYPE] = (new Discriminator())
                    ->setType(Operator\LessThanOperator::TYPE)
                    ->setName('is less than to')
                    ->setClass(Operator\LessThanOperator::class)
                    ->setDescription('');

                return $this->container[Operator\LessThanOperator::TYPE];

            case ProductClassRule::TYPE:
                $this->container[ProductClassRule::TYPE] = (new Discriminator())
                    ->setType(ProductClassRule::TYPE)
                    ->setName('Product Class Rule')
                    ->setClass(ProductClassRule::class)
                    ->setDescription('');

                return $this->container[ProductClassRule::TYPE];

            case CartRule::TYPE:
                $this->container[CartRule::TYPE] = (new Discriminator())
                    ->setType(CartRule::TYPE)
                    ->setName('Cart Rule')
                    ->setClass(CartRule::class)
                    ->setDescription('');

                return $this->container[CartRule::TYPE];

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

            case CartTotalCondition::TYPE:
                $this->container[CartTotalCondition::TYPE] = (new Discriminator())
                    ->setType(CartTotalCondition::TYPE)
                    ->setName('Cart Total Condition')
                    ->setClass(CartTotalCondition::class)
                    ->setDescription('');

                return $this->container[CartTotalCondition::TYPE];

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

            case CartTotalAmountPromotion::TYPE:
                $this->container[CartTotalAmountPromotion::TYPE] = (new Discriminator())
                    ->setType(CartTotalAmountPromotion::TYPE)
                    ->setName('Cart Total Amount Promotion')
                    ->setClass(CartTotalAmountPromotion::class)
                    ->setDescription('');

                return $this->container[CartTotalAmountPromotion::TYPE];

            case CartTotalPercentPromotion::TYPE:
                $this->container[CartTotalPercentPromotion::TYPE] = (new Discriminator())
                    ->setType(CartTotalPercentPromotion::TYPE)
                    ->setName('Cart Total Percent Promotion')
                    ->setClass(CartTotalPercentPromotion::class)
                    ->setDescription('');

                return $this->container[CartTotalPercentPromotion::TYPE];

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
