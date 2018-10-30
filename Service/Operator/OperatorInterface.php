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

use Doctrine\ORM\QueryBuilder;
use Plugin\FlashSale\Entity\Condition;

interface OperatorInterface
{
    /**
     * Implement validate logic
     *
     * @param $condition
     * @param $data
     *
     * @return bool
     */
    public function match($condition, $data);

    /**
     * @return string
     */
    public function getType();
}
