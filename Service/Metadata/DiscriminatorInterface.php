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

namespace Plugin\FlashSale\Service\Metadata;

interface DiscriminatorInterface
{
    /**
     * Get type
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription(): string;

    /**
     * Get class
     *
     * @return string
     */
    public function getClass(): string;
}
