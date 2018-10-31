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
use Plugin\FlashSale\Entity\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductClassIdCondition extends Condition
{
    const TYPE = 'condition_product_class_id';

    /**
     * {@inheritdoc}
     *
     * @param $data
     *
     * @return bool
     */
    public function match($ProductClass)
    {
        /** @var ProductClass $ProductClass */
        if (!$ProductClass instanceof ProductClass) {
            return false;
        }

        return $this->operatorFactory
            ->create(['type' => $this->getOperator()])
            ->match($this->value, $ProductClass->getId());
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
