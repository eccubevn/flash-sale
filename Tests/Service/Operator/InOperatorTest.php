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

use Eccube\Entity\Product;
use Eccube\Repository\ProductRepository;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Repository\RuleRepository;
use Plugin\FlashSale\Tests\Service\AbstractServiceTestCase;

class InOperatorTest extends AbstractServiceTestCase
{
    /** @var  OperatorFactory */
    protected $operatorFactory;

    /** @var ProductClassIdCondition */
    protected $ProductClassIdCondition;

    /** @var RuleRepository */
    protected $ruleRepository;

    /** @var ProductRepository */
    protected $productRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->operatorFactory = $this->container->get(OperatorFactory::class);
        $this->ruleRepository = $this->container->get(RuleRepository::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
    }

    /**
     *  ?????
     */
    public function testParseCondition()
    {
        $data = $this->createFlashSaleAndRules(__METHOD__ . 'test only');
        /** @var Product $Product */
        $Product = $data['Product'];
        $prodClassIds = [];
        foreach ($Product->getProductClasses() as $productClass) {
            $prodClassIds[] = $productClass->getId();
        }

        /** @var Rule[] $Rules */
        $Rules = $this->ruleRepository->getAllRule();

        $prodRepository = $this->productRepository;
        foreach ($Rules as $rule) {
            $qbItem = $prodRepository->createQueryBuilder('p');
            $qbItem->join('p.ProductClasses', 'pc')
                ->groupBy('p');
            $conditions = $rule->getConditions();
            foreach ($conditions as $condition) {
                if ($condition instanceof ProductClassIdCondition) {
                    $condOperator = $condition->getOperator();
                    $Condition = $this->operatorFactory->createByType($condOperator);
                    $qbItem = $Condition->parseCondition($qbItem, $condition);

                    $this->expected = true;
                    $this->actual = (strpos($qbItem->getQuery()->getSQL(), implode(', ', $prodClassIds)) !== false);
                    $this->verify();
                }
            }
        }
    }
}
