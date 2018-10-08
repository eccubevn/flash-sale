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

use Plugin\FlashSale\Repository\RuleRepository;

/**
 * Class RuleRepositoryTest
 */
class RuleRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var RuleRepository
     */
    protected $ruleRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->ruleRepository = $this->container->get(RuleRepository::class);
    }

    public function testAvailableAllRule()
    {
        $this->createFlashSaleAndRules('Test rule');
        $data = $this->ruleRepository->getAllRule();

        $this->expected = 1;
        $this->actual = count($data);
        $this->verify();
    }
}
