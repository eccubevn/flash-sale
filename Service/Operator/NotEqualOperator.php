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
        if (is_array($data)) {
            return (bool) !in_array($condition, $data);
        }

        return $condition != $data;
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getName(): string
    {
        return 'is not equal to';
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
