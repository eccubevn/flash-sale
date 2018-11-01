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

namespace Plugin\FlashSale\Tests\Repository;

use Plugin\FlashSale\Repository\ConditionRepository;

/**
 * Class RuleRepositoryTest
 */
class ConditionRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var ConditionRepository
     */
    protected $conditionRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->conditionRepository = $this->container->get(ConditionRepository::class);
    }

    public function testProductList()
    {
        $this->entityManager->clear();

        $data = $this->conditionRepository->getProductList();
        $product = current($data);

        $this->expected = true;
        $this->actual = isset($product['product']);
        $this->verify();
    }

    public function testProductListEmpty()
    {
        $this->deleteAllRows($this->tables);
        $this->entityManager->clear();

        $data = $this->conditionRepository->getProductList();
        $this->expected = [];
        $this->actual = $data;
        $this->verify();
    }
}
