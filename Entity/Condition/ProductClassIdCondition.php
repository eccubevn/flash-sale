<?php
namespace Plugin\FlashSale\Entity\Condition;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Entity\Condition;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductClassIdCondition extends Condition implements ConditionInterface
{
    const TYPE = 'condition_product_class_id';

    /**
     * @var Operator\OperatorFactory
     */
    protected $operatorFactory;

    /**
     *
     * @param Operator\OperatorFactory $operatorFactory
     * @return $this
     * @required
     */
    public function setOperatorFactory(Operator\OperatorFactory $operatorFactory)
    {
        $this->operatorFactory = $operatorFactory;
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param $data
     * @return bool
     */
    public function match($ProductClass)
    {
        /** @var ProductClass $ProductClass */
        if (!$ProductClass instanceof ProductClass) {
            return false;
        }

        return $this->operatorFactory->createByType($this->getOperator())->match($this->value, $ProductClass->getId());
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getOperatorTypes(): array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\NotEqualOperator::TYPE,
        ];
    }
}