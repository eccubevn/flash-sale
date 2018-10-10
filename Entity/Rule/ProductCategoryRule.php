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
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Operator as Operator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;

/**
 * @ORM\Entity
 */
class ProductCategoryRule extends Rule implements RuleInterface
{
    const TYPE = 'rule_product_category';

    /**
     * @var array
     */
    protected $cached;

    /**
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
     * @var DiscriminatorManager
     */
    protected $discriminatorManager;

    /**
     * Set $operatorFactory
     *
     * @param Operator\OperatorFactory $operatorFactory
     *
     * @return $this
     * @required
     */
    public function setOperatorFactory(Operator\OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;

        return $this;
    }

    /**
     * @param DiscriminatorManager $discriminatorManager
     *
     * @return $this
     * @required
     */
    public function setDiscriminatorManager(DiscriminatorManager $discriminatorManager)
    {
        $this->discriminatorManager = $discriminatorManager;
        $this->discriminator = $discriminatorManager->get(static::TYPE);

        return $this;
    }

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
            ->createByType($this->getOperator())->match($this->getConditions(), $ProductClass);

        return $this->cached[__METHOD__ . $ProductClass->getId()];
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     *
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($ProductClass): array
    {
        if (!$ProductClass instanceof ProductClass) {
            return [];
        }

        if (!$this->match($ProductClass)) {
            return [];
        }

        if (isset($this->cached[__METHOD__ . $ProductClass->getId()])) {
            return $this->cached[__METHOD__ . $ProductClass->getId()];
        }

        $this->cached[__METHOD__ . $ProductClass->getId()] = $this->getPromotion()->getDiscountItems($ProductClass);

        return $this->cached[__METHOD__ . $ProductClass->getId()];
    }
}
