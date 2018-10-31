<?php
namespace Plugin\FlashSale\Tests\Factory;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Factory\ConditionFactory;
use Plugin\FlashSale\Entity\Condition as Condition;

class ConditionFactoryTest extends EccubeTestCase
{
    /**
     * @var ConditionFactory
     */
    protected $conditionFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->conditionFactory = new ConditionFactory();
    }

    public function testCreate_InvalidTypeNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->conditionFactory->create();
    }

    public function testCreate_InvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->conditionFactory->create(['type' => 'fy']);
    }

    /**
     * @param $data
     * @param $expectedClass
     * @param $expectedOperator
     * @param $expectedValue
     * @dataProvider dataProvider_testCreate_Valid
     */
    public function testCreate_Valid($data, $expectedClass, $expectedOperator, $expectedValue)
    {
        $condition = $this->conditionFactory->create($data);
        $this->assertEquals($expectedClass, get_class($condition));
        $this->assertEquals($expectedOperator, $condition->getOperator());
        $this->assertEquals($expectedValue, $condition->getValue());
    }

    public function dataProvider_testCreate_Valid()
    {
        return [
            [
                [
                    'type' => Condition\CartTotalCondition::TYPE,
                    'operator' => '',
                    'value' => 111
                ],
                Condition\CartTotalCondition::class,
                '',
                111
            ],
            [
                [
                    'type' => Condition\ProductClassIdCondition::TYPE,
                    'operator' => 'operator_in',
                    'value' => 222
                ],
                Condition\ProductClassIdCondition::class,
                'operator_in',
                222
            ],
            [
                [
                    'type' => Condition\ProductCategoryIdCondition::TYPE,
                    'operator' => 'operator_all',
                    'value' => 333
                ],
                Condition\ProductCategoryIdCondition::class,
                'operator_all',
                333
            ],
        ];
    }
}
