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

namespace Plugin\FlashSale\Entity\Promotion;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ItemInterface;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\OrderItem;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Entity\Promotion;
use Plugin\FlashSale\Entity\DiscountInterface;
use Plugin\FlashSale\Entity\Discount;

/**
 * @ORM\Entity
 */
class ProductClassPricePercentPromotion extends Promotion implements PromotionInterface
{
    const TYPE = 'promotion_product_class_price_percent';

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Set $entityManager
     *
     * @param EntityManagerInterface $entityManager
     *
     * @return $this
     * @required
     */
    public function setEntityManager(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param $object
     * @return DiscountInterface
     */
    public function getDiscount($object)
    {
        $discount = new Discount();
        $discount->setPromotionId($this->getId());

        if (!$object instanceof ProductClass) {
            return $discount;
        }

        $discount->setValue(floor($object->getPrice02IncTax() / 100 * $this->getValue()));

        return $discount;
    }
}
