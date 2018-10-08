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
        parent::setUp();
    }

    public function testConstructor()
    {
        $FlashSale = new FlashSale();
        $FlashSale->setStatus(FlashSale::STATUS_ACTIVATED);

        $this->expected = 0;

        $this->actual = $FlashSale->getId();
        $this->verify();

        $this->actual = $FlashSale->getName();
        $this->verify();

        $this->actual = $FlashSale->getFromTime();
        $this->verify();

        $this->actual = $FlashSale->getToTime();
        $this->verify();

        $this->expected = FlashSale::STATUS_ACTIVATED;
        $this->actual = $FlashSale->getStatus();
        $this->verify();

        $this->expected = FlashSale::$statusList[FlashSale::STATUS_ACTIVATED];
        $this->actual = $FlashSale->getStatusText();
        $this->verify();
    }
}
