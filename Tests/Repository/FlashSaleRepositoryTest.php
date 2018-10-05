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

use Plugin\FlashSale\Entity\FlashSale;
use Plugin\FlashSale\Repository\FlashSaleRepository;

/**
 * Class FlashSaleRepositoryTest
 */
class FlashSaleRepositoryTest extends AbstractRepositoryTestCase
{
    /**
     * @var FlashSaleRepository
     */
    protected $flashSaleRepository;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->flashSaleRepository = $this->container->get(FlashSaleRepository::class);
    }

    public function testAvailableFlashSale()
    {
        $FlashSale = $this->flashSaleRepository->getAvailableFlashSale();
        $this->expected = $this->eventName;
        $this->actual = $FlashSale->getName();
        $this->verify();
    }

    public function testDelete()
    {
        // make sure created success
        $FlashSale = $this->createFlashSaleAndRules('Create to delete - test only');
        $countAll = $this->flashSaleRepository->count(['status' => FlashSale::STATUS_ACTIVATED]);

        $this->expected = 2;
        $this->actual = $countAll;
        $this->verify();

        // Test delete
        $this->flashSaleRepository->delete($FlashSale);
        $countActivated = $this->flashSaleRepository->count(['status' => FlashSale::STATUS_ACTIVATED]);
        $this->expected = 1;
        $this->actual = $countActivated;
        $this->verify();
    }

    public function testSave()
    {
        $faker = $this->getFaker();
        $name = $faker->name;
        $FlashSale = new FlashSale();
        $FlashSale->setName($name);
        $FlashSale->setFromTime(new \DateTime((date('Y-m-d')).' 00:00:00'));
        $FlashSale->setToTime(new \DateTime((date('Y-m-d')).' 23:59:59'));
        $FlashSale->setStatus(FlashSale::STATUS_ACTIVATED);
        $FlashSale->setCreatedAt(new \DateTime());
        $FlashSale->setUpdatedAt(new \DateTime());
        $this->flashSaleRepository->save($FlashSale);

        $this->expected = $name;
        $this->actual = $this->flashSaleRepository->findOneBy(['name' => $name])->getName();
        $this->verify();
    }
}
