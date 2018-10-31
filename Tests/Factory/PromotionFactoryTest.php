<?php
namespace Plugin\FlashSale\Tests\Factory;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Factory\PromotionFactory;
use Plugin\FlashSale\Entity\Promotion as Promotion;

class PromotionFactoryTest extends EccubeTestCase
{
    /**
     * @var PromotionFactory
     */
    protected $promotionFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->promotionFactory = new PromotionFactory();
    }

    public function testCreate_InvalidTypeNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->promotionFactory->create();
    }

    public function testCreate_InvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->promotionFactory->create(['type' => 'fy']);
    }

    /**
     * @param $data
     * @param $expectedClass
     * @param $expectedValue
     * @dataProvider dataProvider_testCreate_Valid
     */
    public function testCreate_Valid($data, $expectedClass, $expectedValue)
    {
        $Rule = $this->promotionFactory->create($data);
        $this->assertEquals($expectedClass, get_class($Rule));
        $this->assertEquals($expectedValue, $Rule->getValue());
    }

    public function dataProvider_testCreate_Valid()
    {
        return [
            [
                [
                    'type' => Promotion\ProductClassPricePercentPromotion::TYPE,
                    'value' => 111
                ],
                Promotion\ProductClassPricePercentPromotion::class,
                111
            ],
            [
                [
                    'type' => Promotion\ProductClassPriceAmountPromotion::TYPE,
                    'value' => 111
                ],
                Promotion\ProductClassPriceAmountPromotion::class,
                111
            ],
            [
                [
                    'type' => Promotion\CartTotalPercentPromotion::TYPE,
                    'value' => 111
                ],
                Promotion\CartTotalPercentPromotion::class,
                111
            ],
            [
                [
                    'type' => Promotion\CartTotalAmountPromotion::TYPE,
                    'value' => 111
                ],
                Promotion\CartTotalAmountPromotion::class,
                111
            ],
        ];
    }
}
