<?php
namespace Plugin\FlashSale\Tests\Entity\Rule;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Factory\PromotionFactory;
use Plugin\FlashSale\Entity\Promotion as Promotion;

class PromotionTest extends EccubeTestCase
{
    /**
     * @var Promotion
     */
    protected $promotion;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $promotionFactory = new PromotionFactory();

        $this->promotion = $promotionFactory->create([
            'type' => Promotion\CartTotalPercentPromotion::TYPE,
            'value' => 100
        ]);
        $this->promotion->setId(1);
    }

    public function testRawData_DataNull()
    {
        $this->expected = [
            'id' => 1,
            'type' => Promotion\CartTotalPercentPromotion::TYPE,
            'value' => 100,
        ];
        $this->actual = $this->promotion->rawData();
        $this->verify();
    }

    public function testRawData_DataJson()
    {
        $this->expected = [
            'id' => 2,
            'type' => Promotion\CartTotalPercentPromotion::TYPE,
            'value' => 100,
        ];
        $this->actual = $this->promotion->rawData('{"id":"2","type":"'.Promotion\CartTotalPercentPromotion::TYPE.'","value":"100"}');
        $this->verify();
    }
}
