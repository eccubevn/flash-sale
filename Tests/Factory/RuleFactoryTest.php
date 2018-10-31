<?php
namespace Plugin\FlashSale\Tests\Factory;

use Eccube\Tests\EccubeTestCase;
use Plugin\FlashSale\Factory\RuleFactory;
use Plugin\FlashSale\Entity\Rule as Rule;

class RuleFactoryTest extends EccubeTestCase
{
    /**
     * @var RuleFactory
     */
    protected $ruleFactory;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->ruleFactory = new RuleFactory();
    }

    public function testCreate_InvalidTypeNull()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->ruleFactory->create();
    }

    public function testCreate_InvalidType()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->ruleFactory->create(['type' => 'fy']);
    }

    /**
     * @param $data
     * @param $expectedClass
     * @param $expectedOperator
     * @dataProvider dataProvider_testCreate_Valid
     */
    public function testCreate_Valid($data, $expectedClass, $expectedOperator)
    {
        $rule = $this->ruleFactory->create($data);
        $this->assertEquals($expectedClass, get_class($rule));
        $this->assertEquals($expectedOperator, $rule->getOperator());
    }

    public function dataProvider_testCreate_Valid()
    {
        return [
            [
                [
                    'type' => Rule\ProductClassRule::TYPE,
                    'operator' => ''
                ],
                Rule\ProductClassRule::class,
                ''
            ],
            [
                [
                    'type' => Rule\CartRule::TYPE,
                    'operator' => 'operator_in'
                ],
                Rule\CartRule::class,
                'operator_in'
            ]
        ];
    }
}
