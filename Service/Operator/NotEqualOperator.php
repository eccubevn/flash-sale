<?php

/*
 * This file is part of the Flash Sale plugin
 *
 * Copyright(c) ECCUBE VN LAB. All Rights Reserved.
 *
 * https://www.facebook.com/groups/eccube.vn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\FlashSale\Service\Operator;

class NotEqualOperator implements OperatorInterface
{
    const TYPE = 'operator_not_equal';

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
        return $condition != $data;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return trans('flash_sale.admin.form.rule.operator.is_not_equal_to');
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
