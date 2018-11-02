<?php
namespace Plugin\FlashSale\Tests\DataProvide\Service\Operator;

use Symfony\Component\DependencyInjection\Container;
use Plugin\FlashSale\Entity\Condition as Condition;
use Plugin\FlashSale\Service\Operator\OperatorFactory;
use Plugin\FlashSale\Tests\DataProvider\Entity\Condition\CartTotalConditionDataProvider;

class AllOperatorDataProvider
{
    /**
     * @var Container
     */
    protected static $container;

    public function setContainer(Container $container)
    {
        static::$container = $container;
    }

    public static function testMatch_True1()
    {
        list(
            $conditionData['conditionData'],
            $conditionData['Order'],
            $conditionData['expected']
            ) = CartTotalConditionDataProvider::testMatch_EqualOperator_True1();
        $condition1 = new Condition\CartTotalCondition();
        $condition1->setOperatorFactory(static::$container->get(OperatorFactory::class));
        $condition1->setId($conditionData['id']);
        $condition1->setValue($conditionData['value']);
        $condition1->setOperator($conditionData['operator']);
        $expected = $conditionData['expected'] && $conditionData['expected'];

        return [
            [$condition1, $condition1],
            $conditionData['Order'],

        ];
    }
}
