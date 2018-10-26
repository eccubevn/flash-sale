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

use Plugin\FlashSale\Entity\OperatorInterface;
use Plugin\FlashSale\Entity\Operator as Operator;

class OperatorFactory
{
    /**
     * Create operator
     *
     * @param array $options
     *
     * @return OperatorInterface
     */
    public function create($options = []): OperatorInterface
    {
        if (!isset($options['type'])) {
            throw new \InvalidArgumentException('$options must contain "type" value');
        }

        switch ($options['type']) {
            case Operator\AllOperator::TYPE:
                return new Operator\AllOperator();

            case Operator\EqualOperator::TYPE:
                return new Operator\EqualOperator();

            case Operator\InOperator::TYPE:
                return new Operator\InOperator();

            case Operator\NotEqualOperator::TYPE:
                return new Operator\NotEqualOperator();

            case Operator\GreaterThanOperator::TYPE:
                return new Operator\GreaterThanOperator();

            case Operator\LessThanOperator::TYPE:
                return new Operator\LessThanOperator();
        }

        throw new \InvalidArgumentException('Not found operator have type '. $options['type']);
    }
}
