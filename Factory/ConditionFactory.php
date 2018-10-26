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

namespace Plugin\FlashSale\Factory;

use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Condition\CartTotalCondition;

class ConditionFactory
{
    /**
     * Create Condition from array
     *
     * @param array $options
     *
     * @return Condition
     */
    public function create(array $options =[])
    {
        if (!isset($options['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($options['type']) {
            case Condition\ProductClassIdCondition::TYPE:
                $Condition = new Condition\ProductClassIdCondition();
                break;
            case Condition\ProductCategoryIdCondition::TYPE:
                $Condition = new Condition\ProductCategoryIdCondition();
                break;
            case CartTotalCondition::TYPE:
                $Condition = new CartTotalCondition();
                break;
            default:
                throw new \InvalidArgumentException($options['type'].' unsupported');
        }

        if (isset($options['value'])) {
            $Condition->setValue($options['value']);
        }
        if (isset($options['operator'])) {
            $Condition->setOperator($options['operator']);
        }

        return $Condition;
    }
}
