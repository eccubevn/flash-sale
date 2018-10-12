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

namespace Plugin\FlashSale\Tests\Service\Metadata;

use Plugin\FlashSale\Service\Metadata\Discriminator;
use Plugin\FlashSale\Service\Metadata\DiscriminatorManager;
use Plugin\FlashSale\Service\Operator\EqualOperator;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class DiscriminatorManagerTest extends AbstractServiceTestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

    }

    public function testCreate_EqualOperator()
    {
        $DiscriminatorManager = new DiscriminatorManager();
        $data = $DiscriminatorManager->create(EqualOperator::TYPE);
        $test = (new Discriminator())
            ->setType(EqualOperator::TYPE)
            ->setName('is equal to')
            ->setClass(EqualOperator::class)
            ->setDescription('');

        self::assertEquals($test, $data);
    }

    public function testCreate_InvalidArgumentException()
    {
        $discriminatorType = 'test only';
        $DiscriminatorManager = new DiscriminatorManager();
        try {
            $data = $DiscriminatorManager->create($discriminatorType);
        } catch (\Exception $exception) {
            $data = false;
        }

        self::assertFalse($data);
    }
}
