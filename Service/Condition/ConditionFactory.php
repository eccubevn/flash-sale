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

namespace Plugin\FlashSale\Service\Condition;

use Plugin\FlashSale\Entity\Condition;

class ConditionFactory
{
    /**
     * Create Condition from array
     *
     * @param array $data
     *
     * @return Condition
     */
    public static function createFromArray(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($data['type']) {
            case Condition\ProductClassIdCondition::TYPE:
                $Condition = new Condition\ProductClassIdCondition();
                break;
            case Condition\ProductCategoryIdCondition::TYPE:
                $Condition = new Condition\ProductCategoryIdCondition();
                break;
            default:
                throw new \InvalidArgumentException($data['type'].' unsupported');
        }

        if (isset($data['value'])) {
            $Condition->setValue($data['value']);
        }
        if (isset($data['operator'])) {
            $Condition->setOperator($data['operator']);
        }

        return $Condition;
    }
}
