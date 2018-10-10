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

namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductCategoryIdCondition extends Condition implements ConditionInterface
{
    const TYPE = 'condition_product_category_id';

    /**
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
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
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($ProductClass)
    {
        // TODO: check


        /** @var ProductClass $ProductClass */
        if (!$ProductClass instanceof ProductClass) {
            return false;
        }

        return $this->operatorFactory->createByType($this->getOperator())->match($this->value, $ProductClass->getId());
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
            Operator\NotEqualOperator::TYPE,
        ];
    }
}
