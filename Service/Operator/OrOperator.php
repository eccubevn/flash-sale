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

use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Plugin\FlashSale\Service\Condition\ConditionInterface;

class OrOperator implements OperatorInterface
{
    const TYPE = 'operator_or';

    /**
     * {@inheritdoc}
     *
     * @param $condition
     * @param $data
     *
     * @return bool
     */
    public function match($condition, $data)
    {
        if (!$condition instanceof DoctrineCollection && !is_array($condition)) {
            return false;
        }

        foreach ($condition as $cond) {
            if (!$cond instanceof ConditionInterface) {
                return false;
            }

            if ($cond->match($data)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return trans('flash_sale.admin.form.rule.operator.is_one_of');
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}