<?php
namespace Plugin\FlashSale\Tests\Entity;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Promotion;

abstract class PromotionTest extends EccubeTestCase
{
    /**
     * @var Promotion
     */
    protected $promotion;

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Scenario1
     */
    public function testRawData_Scenario0($expected)
    {
        $actual = $this->promotion->rawData(json_encode($expected));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Scenario1
     */
    public function testRawData_Scenario1($expected)
    {
        $this->promotion->setId($expected['id']);
        $this->promotion->setValue($expected['value']);
        $actual = $this->promotion->rawData();
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testRawData_Scenario1()
    {
        return [];
    }

}