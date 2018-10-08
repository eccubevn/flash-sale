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

namespace Plugin\FlashSale\Service\Operator;

class OperatorFactory
{
    /**
     * Create operator
     *
     * @param $type
     *
     * @return OperatorInterface
     */
    public function createByType($type)
    {
        switch ($type) {
            case AllOperator::TYPE:
                return new AllOperator();

            case EqualOperator::TYPE:
                return new EqualOperator();

            case InOperator::TYPE:
                return new InOperator();

            case NotEqualOperator::TYPE:
                return new NotEqualOperator();
        }

        throw new \InvalidArgumentException('Not found operator have type '.$type);
    }
}
