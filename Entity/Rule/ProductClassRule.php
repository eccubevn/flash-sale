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

namespace Plugin\FlashSale\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Entity\DiscountInterface;
use Plugin\FlashSale\Entity\Discount;
use Plugin\FlashSale\Factory\OperatorFactory;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule
{
    const TYPE = 'rule_product_class';

    /**
     * @var array
     */
    protected $cached;

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getConditionTypes(): array
    {
        return [
            ProductClassIdCondition::TYPE,
            ProductCategoryIdCondition::TYPE,
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPromotionTypes(): array
    {
        return [
            ProductClassPricePercentPromotion::TYPE,
            ProductClassPriceAmountPromotion::TYPE,
        ];
    }

    /**
     * Check a product class is matching condition
     *
     * @param ProductClass $ProductClass
     *
     * @return bool
     */
    public function match($ProductClass): bool
    {
        if (!$ProductClass instanceof ProductClass) {
            return false;
        }

        if (isset($this->cached[__METHOD__ . $ProductClass->getId()])) {
            return $this->cached[__METHOD__ . $ProductClass->getId()];
        }

        $this->cached[__METHOD__ . $ProductClass->getId()] = $this->operatorFactory
            ->create(['type' => $this->getOperator()])
            ->match($this->getConditions(), $ProductClass);

        return $this->cached[__METHOD__ . $ProductClass->getId()];
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     * @return DiscountInterface
     */
    public function getDiscount($object): DiscountInterface
    {
        if (!$object instanceof ProductClass) {
            $discount = new Discount();
            $discount->setRuleId($this->getId());
            return $discount;
        }

        return $this->getDiscountFromProductClass($object);
    }

    /**
     * Get discount items of productClass
     *
     * @param ProductClass $ProductClass
     * @return DiscountInterface
     */
    protected function getDiscountFromProductClass(ProductClass $ProductClass)
    {
        $discount = new Discount();
        $discount->setRuleId($this->getId());
        if (!$this->match($ProductClass)) {
            return $discount;
        }

        if (isset($this->cached[__METHOD__ . $ProductClass->getId()])) {
            return $this->cached[__METHOD__ . $ProductClass->getId()];
        }

        $discount = $this->getPromotion()->getDiscount($ProductClass);
        $discount->setRuleId($this->getId());
        $this->cached[__METHOD__ . $ProductClass->getId()] = $discount;

        return $this->cached[__METHOD__ . $ProductClass->getId()];
    }
}
