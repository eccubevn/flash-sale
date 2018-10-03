<?php
namespace Plugin\FlashSale\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\ProductClass;
use Plugin\FlashSale\Service\Rule\RuleInterface;
use Plugin\FlashSale\Service\Condition\ConditionInterface;
use Plugin\FlashSale\Service\Promotion\PromotionInterface;
use Plugin\FlashSale\Service\Operator as Operator;

/**
 * @ORM\Entity
 */
class ProductClassRule extends Rule implements RuleInterface
{
    const TYPE = 'rule_product_class';

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
     * @return array
     */
    public function getOperatorTypes() : array
    {
        return [
            Operator\InOperator::TYPE,
            Operator\AllOperator::TYPE
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getConditionTypes(): array
    {
        return [
            ProductClassCondition::TYPE
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function getPromotionTypes(): array
    {
        return [
            AmountPromotion::TYPE
        ];
    }

    /**
     * Check a product class is matching condition
     *
     * @param ProductClass $ProductClass
     *
     * @return bool
     */
    public function match(ProductClass $ProductClass)
    {
        return $this->operatorFactory->createByType($this->getOperator())->match($this->getConditions(), $ProductClass);
    }
}
