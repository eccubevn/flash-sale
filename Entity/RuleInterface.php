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

namespace Plugin\FlashSale\Entity;

use Plugin\FlashSale\Service\Metadata\DiscriminatorInterface;

interface RuleInterface
{
    /**
     * Get discriminator type
     *
     * @return DiscriminatorInterface
     */
    public function getDiscriminator(): DiscriminatorInterface;

    /**
     * Get operator types
     *
     * @return array
     */
    public function getOperatorTypes(): array;

    /**
     * Get condition types
     *
     * @return array
     */
    public function getConditionTypes(): array;

    /**
     * Get promotion types
     *
     * @return array
     */
    public function getPromotionTypes(): array;

    /**
     * Check a object match conditions of rule
     *
     * @param $object
     *
     * @return bool
     */
    public function match($object): bool;

    /**
     * Get discount item
     *
     * @param $object
     *
     * @return DiscountInterface
     */
    public function getDiscount($object): DiscountInterface;
}
