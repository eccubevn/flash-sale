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

namespace Plugin\FlashSale\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\FlashSale;

/**
 * AbstractEntity test cases.
 *
 * @author Kentaro Ohkouchi
 */
class FlashSaleTest extends EccubeTestCase
{
    public function setUp()
    {
        $this->markTestIncomplete();
        parent::setUp();
    }

    public function testConstructor()
    {
        $this->markTestIncomplete();
        $FlashSale = new FlashSale();
        $FlashSale->setStatus(FlashSale::STATUS_ACTIVATED);

        $this->expected = new ArrayCollection();
        $this->actual = $FlashSale->getRules();
        $this->verify();

        $this->expected = 0;

        $this->actual = $FlashSale->getId();
        $this->verify();

        $this->actual = $FlashSale->getName();
        $this->verify();

        $this->actual = get_class($FlashSale->getFromTime());
        $this->expected = \DateTime::class;
        $this->verify();

        $this->actual = get_class($FlashSale->getToTime());
        $this->expected = \DateTime::class;
        $this->verify();

        $this->expected = FlashSale::STATUS_ACTIVATED;
        $this->actual = $FlashSale->getStatus();
        $this->verify();

        $this->expected = FlashSale::$statusList[FlashSale::STATUS_ACTIVATED];
        $this->actual = $FlashSale->getStatusText();
        $this->verify();
    }
}
