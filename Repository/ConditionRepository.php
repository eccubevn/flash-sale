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

namespace Plugin\FlashSale\Repository;

use Eccube\Entity\Product;
use Eccube\Repository\AbstractRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Entity\Rule;
use Plugin\FlashSale\Service\Operator\OperatorFactory;

class ConditionRepository extends AbstractRepository
{
    /**
     * @var OperatorFactory
     */
    private $operatorFactory;

    /**
     * @var FlashSaleRepository
     */
    private $fsRepository;

    /**
     * ConditionRepository constructor.
     *
     * @param ManagerRegistry $registry
     * @param OperatorFactory $operatorFactory
     * @param FlashSaleRepository $flashSaleRepository
     */
    public function __construct(ManagerRegistry $registry, OperatorFactory $operatorFactory, FlashSaleRepository $flashSaleRepository)
    {
        parent::__construct($registry, Condition::class);
        $this->operatorFactory = $operatorFactory;
        $this->fsRepository = $flashSaleRepository;
    }

    /**
     * @return mixed
     */
    public function getProductList()
    {
        $fs = $this->fsRepository->getAvailableFlashSale();
        if (!$fs) {
            return [];
        }
        /** @var Rule[] $Rules */
        $Rules = $fs->getRules();
        $arrayProductTmp = [];

        $prodRepository = $this->getEntityManager()->getRepository('Eccube\Entity\Product');
        foreach ($Rules as $Rule) {
            if (!$Rule instanceof Rule\ProductClassRule) {
                continue;
            }
            $qbItem = $prodRepository->createQueryBuilder('p');
            $ruleOperatorName = $Rule->getOperator();
            $operatorRule = $this->operatorFactory->createByType($ruleOperatorName);
            $qbItem = $Rule->createQueryBuilder($qbItem, $operatorRule);

            /** @var Product $Product */
            foreach ($qbItem->getQuery()->getResult() as $Product) {
                $tmp = [];
                foreach ($Product->getProductClasses() as $ProductClass) {
                    // discount include tax???
                    $discount = $Rule->getDiscount($ProductClass);
                    $discountPrice = $ProductClass->getPrice02IncTax() - $discount->getValue();
                    $discountPercent = 100 - floor($discountPrice * 100 / $ProductClass->getPrice02IncTax());
                    $tmp[$ProductClass->getId()] = $discountPercent;
                }
                $arrayProductTmp[$Product->getId()]['promotion'] = max($tmp);
                $arrayProductTmp[$Product->getId()]['product'] = $Product;
            }
        }

        return $arrayProductTmp;
    }
}
