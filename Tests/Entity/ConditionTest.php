<?php
namespace Plugin\FlashSale\Tests\Entity;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition;

abstract class ConditionTest extends EccubeTestCase
{
    /**
     * @var Condition
     */
    protected $condition;

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Valid
     */
    public function testRawData_Valid_Json($expected)
    {
        $actual = $this->condition->rawData(json_encode($expected));
        $this->assertEquals($expected, $actual);
    }

    /**
     * @param $expected
     * @dataProvider dataProvider_testRawData_Valid
     */
    public function testRawData_Valid($expected)
    {
        $this->condition->setId($expected['id']);
        $this->condition->setOperator($expected['operator']);
        $this->condition->setValue($expected['value']);
        $actual = $this->condition->rawData();
        $this->assertEquals($expected, $actual);
    }

    public static function dataProvider_testRawData_Valid()
    {
        return [];
    }

}
