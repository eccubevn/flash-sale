<?php
namespace Plugin\FlashSale\Tests\Entity\Rule;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Factory\ConditionFactory;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Operator as Operator;

class ConditionTest extends EccubeTestCase
{
    /**
     * @var Condition
     */
    protected $condition;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $conditionFactory = new ConditionFactory();

        $this->condition = $conditionFactory->create([
            'type' => Condition\CartTotalCondition::TYPE,
            'value' => 100,
            'operator' => Operator\GreaterThanOperator::TYPE
        ]);
        $this->condition->setId(1);
    }

    public function testRawData_DataNull()
    {
        $this->expected = [
            'id' => 1,
            'type' => Condition\CartTotalCondition::TYPE,
            'operator' =>  Operator\GreaterThanOperator::TYPE,
            'value' => 100,
        ];
        $this->actual = $this->condition->rawData();
        $this->verify();
    }

    public function testRawData_DataJson()
    {
        $this->expected = [
            'id' => 2,
            'type' => Condition\CartTotalCondition::TYPE,
            'operator' =>  Operator\GreaterThanOperator::TYPE,
            'value' => 100,
        ];
        $this->actual = $this->condition->rawData(json_encode($this->expected));
        $this->verify();
    }
}
