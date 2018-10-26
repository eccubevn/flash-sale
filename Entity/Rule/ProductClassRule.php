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
use Eccube\Entity\OrderItem;
use Eccube\Entity\Order;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Condition\ProductCategoryIdCondition;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPriceAmountPromotion;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Factory\OperatorFactory;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule
{
    use \Plugin\FlashSale\Utils\Memoization\MemoizationTrait;

    const TYPE = 'rule_product_class';

    /**
     * @var OperatorFactory
     */
    protected $operatorFactory;

    /**
     * Set $operatorFactory
     *
     * @param OperatorFactory $operatorFactory
     *
     * @return $this
     * @required
     */
    public function setOperatorFactory(OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;

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

        // @codeCoverageIgnoreStart
        if ($this->memoization->has($ProductClass->getId())) {
            return $this->memoization->get($ProductClass->getId());
        }
        // @codeCoverageIgnoreEnd

        $result = $this->operatorFactory
            ->create(['type' => $this->getOperator()])
            ->match($this->getConditions(), $ProductClass);
        $this->memoization->set($result, $ProductClass->getId());

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     *
     * @return \Eccube\Entity\ItemInterface[]
     */
    public function getDiscountItems($object): array
    {
        if ($object instanceof ProductClass) {
            return $this->getDiscountItemsFromProductClass($object);
        } elseif ($object instanceof OrderItem) {
            return $this->getDiscountItemsFromOrderItem($object);
        } elseif ($object instanceof Order) {
            return $this->getDiscountItemsFromOrder($object);
        }

        return [];
    }

    /**
     * Get discount items of productClass
     *
     * @param ProductClass $ProductClass
     * @return OrderItem[]
     */
    protected function getDiscountItemsFromProductClass(ProductClass $ProductClass)
    {
        if (!$this->match($ProductClass)) {
            return [];
        }

        // @codeCoverageIgnoreStart
        if ($this->memoization->has($ProductClass->getId())) {
            return $this->memoization->get($ProductClass->getId());
        }
        // @codeCoverageIgnoreEnd

        $result = $this->getPromotion()->getDiscountItems($ProductClass);
        $this->memoization->set($result, $ProductClass->getId());

        return $result;
    }

    /**
     * Get discount items from order item
     *
     * @param OrderItem $OrderItem
     * @return OrderItem[]
     */
    protected function getDiscountItemsFromOrderItem(OrderItem $OrderItem)
    {
        if (!$OrderItem->isProduct()) {
            return [];
        }

        $result = $this->getDiscountItemsFromProductClass($OrderItem->getProductClass());
        /** @var OrderItem $DiscountItem */
        foreach ($result as $DiscountItem) {
            $DiscountItem->setQuantity($OrderItem->getQuantity());
        }

        return $result;
    }

    /**
     * Get discount items from order
     *
     * @param Order $Order
     * @return OrderItem[]
     */
    protected function getDiscountItemsFromOrder(Order $Order)
    {
        $result = [];
        foreach ($Order->getItems() as $OrderItem) {
            $result = array_merge($result, $this->getDiscountItemsFromOrderItem($OrderItem));
        }

        return $result;
    }
}
