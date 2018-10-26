<?php
namespace Plugin\FlashSale\Tests\Entity\Rule;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Entity\Operator as Operator;
use Plugin\FlashSale\Entity\Rule as Rule;
use Plugin\FlashSale\Entity\Promotion as Promotion;
use Plugin\FlashSale\Factory\RuleFactory;

class RuleTest extends EccubeTestCase
{
    /**
     * @var Rule
     */
    protected $rule;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $ruleFactory = new RuleFactory();

        $this->rule = $ruleFactory->create([
            'type' => Rule\CartRule::TYPE,
            'operator' => Operator\InOperator::TYPE
        ]);
        $this->rule->setId(1);
    }

    public function testRawData_DataNull()
    {
        $conditionRaw = [
            'id' => 1,
            'type' => Condition\CartTotalCondition::TYPE,
            'operator' => Operator\GreaterThanOperator::TYPE,
            'value' => 200
        ];
        $mCondition = $this->getMockBuilder(Condition\CartTotalCondition::class)->getMock();
        $mCondition->method('rawData')->willReturn($conditionRaw);
        $this->rule->addConditions($mCondition);

        $promotionRaw = [
            'id' => 1,
            'type' => Promotion\CartTotalPercentPromotion::TYPE,
            'value' => 5
        ];
        $mPromotion = $this->getMockBuilder(Promotion\CartTotalPercentPromotion::class)->getMock();
        $mPromotion->method('rawData')->willReturn($promotionRaw);
        $this->rule->setPromotion($mPromotion);

        $this->expected = [
            'id' => 1,
            'type' => Rule\CartRule::TYPE,
            'operator' =>  Operator\InOperator::TYPE,
            'conditions' => [
                $conditionRaw
            ],
            'promotion' => $promotionRaw
        ];
        $this->actual = $this->rule->rawData();
        $this->verify();
    }

    public function testRawData_DataJson()
    {
        $conditionRaw = [
            'id' => 1,
            'type' => Condition\CartTotalCondition::TYPE,
            'operator' => Operator\GreaterThanOperator::TYPE,
            'value' => 200
        ];
        $mCondition = $this->getMockBuilder(Condition\CartTotalCondition::class)->getMock();
        $mCondition->method('rawData')->willReturn($conditionRaw);
        $this->rule->addConditions($mCondition);

        $promotionRaw = [
            'id' => 1,
            'type' => Promotion\CartTotalPercentPromotion::TYPE,
            'value' => 5
        ];
        $mPromotion = $this->getMockBuilder(Promotion\CartTotalPercentPromotion::class)->getMock();
        $mPromotion->method('rawData')->willReturn($promotionRaw);
        $this->rule->setPromotion($mPromotion);

        $this->expected = [
            'id' => 2,
            'type' => Rule\CartRule::TYPE,
            'operator' =>  Operator\InOperator::TYPE,
            'conditions' => [
                $conditionRaw
            ],
            'promotion' => $promotionRaw
        ];
        $this->actual = $this->rule->rawData(json_encode($this->expected));
        $this->verify();
    }
}
