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

use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class OperatorFactoryTest extends AbstractServiceTestCase
{
    /** @var  OperatorFactory */
    protected $operatorFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->operatorFactory = $this->container->get(OperatorFactory::class);
    }

    public function testCreateByType()
    {
        $obj = $this->operatorFactory->createByType(AllOperator::TYPE);
        $this->expected = true;
        $this->actual = is_object($obj);
        $this->verify();

        $obj = $this->operatorFactory->createByType(EqualOperator::TYPE);
        $this->expected = true;
        $this->actual = is_object($obj);
        $this->verify();

        $obj = $this->operatorFactory->createByType(InOperator::TYPE);
        $this->expected = true;
        $this->actual = is_object($obj);
        $this->verify();

        $obj = $this->operatorFactory->createByType(NotEqualOperator::TYPE);
        $this->expected = true;
        $this->actual = is_object($obj);
        $this->verify();

        $type = 'type_test_only';
        try {
            $obj = $this->operatorFactory->createByType($type);
        } catch (\Exception $exception) {
            $obj = 'Not found operator have type '.$type;
        }

        $this->expected = 'Not found operator have type '.$type;
        $this->actual = $obj;
        $this->verify();
    }
}
