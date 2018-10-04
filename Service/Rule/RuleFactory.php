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

namespace Plugin\FlashSale\Service\Rule;

use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;

class RuleFactory
{
    /**
     * Create Rule from array
     *
     * @param array $data
     *
     * @return Rule
     */
    public static function createFromArray(array $data)
    {
        switch ($data['type']) {
            case Rule\ProductClassRule::TYPE:
                $Rule = new ProductClassRule();
                break;
            default:
                throw new \InvalidArgumentException('$data[type] must be required');
        }
        if (isset($data['operator'])) {
            $Rule->setOperator($data['operator']);
        }

        return $Rule;
    }
}
