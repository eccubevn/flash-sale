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

use Eccube\Entity\ProductClass;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition\ProductClassIdCondition;
use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\Promotion\ProductClassPricePercentPromotion;
use Plugin\FlashSale\Entity\Rule\ProductClassRule;
use Plugin\FlashSale\Repository\FlashSaleRepository;
use Plugin\FlashSale\Entity\Operator\InOperator;

/**
 * Class AbstractRepositoryTestCase
 */
abstract class AbstractRepositoryTestCase extends EccubeTestCase
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    protected $eventName = 'Event name - test only';

    protected $tables = [
        'plg_flash_sale_promotion',
        'plg_flash_sale_condition',
        'plg_flash_sale_rule',
        'plg_flash_sale_flash_sale',
    ];

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->flashSaleRepository = $this->container->get(FlashSaleRepository::class);

        $this->deleteAllRows($this->tables);
        $this->createFlashSaleAndRules($this->eventName);
    }

    public function createFlashSaleAndRules($evenName)
    {
        $Product = $this->createProduct();
        $rules['rules'] = $this->rulesData($Product);

        $FlashSale = new FlashSale();
        $FlashSale->setName($evenName);
        $FlashSale->setFromTime(new \DateTime());
        $FlashSale->setToTime(new \DateTime((date('Y-m-d')).' 23:59:59'));
        $FlashSale->setStatus(FlashSale::STATUS_ACTIVATED);
        $FlashSale->setCreatedAt(new \DateTime());
        $FlashSale->setUpdatedAt(new \DateTime());
        $this->entityManager->persist($FlashSale);
        $this->entityManager->flush($FlashSale);

        $FlashSale->updateFromArray($rules);
        foreach ($FlashSale->getRules() as $Rule) {
            $Promotion = $Rule->getPromotion();
            if ($Promotion instanceof Promotion) {
                if (isset($Rule->modified)) {
                    $this->entityManager->persist($Promotion);
                } else {
                    $this->entityManager->remove($Promotion);
                }
            }
            foreach ($Rule->getConditions() as $Condition) {
                if (isset($Rule->modified)) {
                    $this->entityManager->persist($Condition);
                } else {
                    $this->entityManager->remove($Condition);
                }
            }

            if (isset($Rule->modified)) {
                $this->entityManager->persist($Rule);
            } else {
                $this->entityManager->remove($Rule);
            }
        }

        return $FlashSale;
    }

    public function rulesData($Product)
    {
        $productClassIds = [];
        /** @var ProductClass $productClass */
        foreach ($Product->getProductClasses() as $productClass) {
            $productClassIds[] = $productClass->getId();
        }

        $rules[] = [
            'id' => '',
            'type' => ProductClassRule::TYPE,
            'operator' => InOperator::TYPE,
            'promotion' => [
                'id' => '',
                'type' => ProductClassPricePercentPromotion::TYPE,
                'value' => 30,
            ],
            'conditions' => [
                [
                    'id' => '',
                    'type' => ProductClassIdCondition::TYPE,
                    'operator' => InOperator::TYPE,
                    'value' => implode(',', $productClassIds),
                ],
            ],
        ];

        return $rules;
    }
}
