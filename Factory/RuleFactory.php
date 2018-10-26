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

use Plugin\FlashSale\Entity\Rule;

class RuleFactory
{
    /**
     * Create Rule from array
     *
     * @param array $options
     *
     * @return Rule
     */
    public function create(array $options)
    {
        if (!isset($options['type'])) {
            throw new \InvalidArgumentException('$data[type] must be required');
        }

        switch ($options['type']) {
            case Rule\ProductClassRule::TYPE:
                $Rule = new Rule\ProductClassRule();
                break;
            case Rule\CartRule::TYPE:
                $Rule = new Rule\CartRule();
                break;
            default:
                throw new \InvalidArgumentException($options['type'].' unsupported');
        }
        if (isset($options['operator'])) {
            $Rule->setOperator($options['operator']);
        }

        return $Rule;
    }
}
